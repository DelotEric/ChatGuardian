<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        $donations = Donation::query()->with('donor')->latest('donated_at')->paginate(10);
        $totalMonth = Donation::query()
            ->whereMonth('donated_at', now()->month)
            ->whereYear('donated_at', now()->year)
            ->sum('amount');

        $donors = Donor::orderBy('name')->get();

        return view('donations.index', compact('donations', 'totalMonth', 'donors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'donor_id' => ['required', 'exists:donors,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'donated_at' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'receipt_number' => ['nullable', 'string', 'max:120'],
            'is_receipt_sent' => ['sometimes', 'boolean'],
        ]);

        $data['is_receipt_sent'] = $request->boolean('is_receipt_sent');

        Donation::create($data);

        return redirect()->route('donations.index')->with('status', 'Don enregistré.');
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filename = 'donations-' . now()->format('Ymd-His') . '.csv';

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Donateur', 'Email', 'Montant', 'Date', 'Paiement', 'N° reçu', 'Reçu envoyé']);

            Donation::with('donor')->orderBy('donated_at', 'desc')->chunk(200, function ($donations) use ($handle) {
                foreach ($donations as $donation) {
                    fputcsv($handle, [
                        optional($donation->donor)->name,
                        optional($donation->donor)->email,
                        number_format($donation->amount, 2, '.', ''),
                        optional($donation->donated_at)->format('Y-m-d'),
                        $donation->payment_method,
                        $donation->receipt_number,
                        $donation->is_receipt_sent ? 'oui' : 'non',
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function createDonor(Request $request): RedirectResponse
    {
        $donorData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $donor = Donor::create($donorData);

        return redirect()->back()->with('status', 'Donateur ajouté : ' . $donor->name);
    }
}
