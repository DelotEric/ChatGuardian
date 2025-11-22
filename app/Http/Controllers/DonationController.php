<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceiptMail;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        $this->authorizeRoles('admin');

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
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'donor_id' => ['required', 'exists:donors,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'donated_at' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'receipt_number' => ['nullable', 'string', 'max:120'],
            'is_receipt_sent' => ['sometimes', 'boolean'],
        ]);

        $data['is_receipt_sent'] = $request->boolean('is_receipt_sent');

        $donation = Donation::create($data);

        $this->logActivity('donation.created', $donation, 'Don enregistré pour ' . $donation->donor->full_name);

        return redirect()->route('donations.index')->with('status', 'Don enregistré.');
    }

    public function update(Request $request, Donation $donation): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'donor_id' => ['required', 'exists:donors,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'donated_at' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'receipt_number' => ['nullable', 'string', 'max:120'],
            'is_receipt_sent' => ['sometimes', 'boolean'],
        ]);

        $data['is_receipt_sent'] = $request->boolean('is_receipt_sent');

        $donation->update($data);

        $this->logActivity('donation.updated', $donation, 'Don mis à jour.');

        return redirect()->route('donations.index')->with('status', 'Don mis à jour.');
    }

    public function destroy(Donation $donation): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $donation->delete();

        $this->logActivity('donation.deleted', $donation, 'Don supprimé.');

        return redirect()->route('donations.index')->with('status', 'Don supprimé.');
    }

    public function sendReceipt(Donation $donation): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $donation->load('donor');

        if (!$donation->donor || !$donation->donor->email) {
            return redirect()->route('donations.index')->with('status', "Impossible d'envoyer le reçu : aucun email pour ce donateur.");
        }

        if (!$donation->receipt_number) {
            $donation->receipt_number = 'REC-' . str_pad((string) $donation->id, 5, '0', STR_PAD_LEFT);
        }

        $donation->is_receipt_sent = true;
        $donation->save();

        Mail::to($donation->donor->email)->send(new DonationReceiptMail($donation));

        $this->logActivity('donation.receipt_sent', $donation, 'Reçu fiscal envoyé par email.');

        return redirect()->route('donations.index')->with('status', 'Reçu fiscal envoyé par email.');
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorizeRoles('admin');

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

}
