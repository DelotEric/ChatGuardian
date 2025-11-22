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

        return view('donations.index', compact('donations', 'totalMonth'));
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
