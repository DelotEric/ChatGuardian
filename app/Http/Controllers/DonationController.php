<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donor;
use App\Mail\DonationReceiptMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
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

        // Auto-generate receipt number if not provided
        if (empty($data['receipt_number'])) {
            $latestDonation = Donation::latest('id')->first();
            $nextNumber = $latestDonation ? ((int) substr($latestDonation->receipt_number, -3)) + 1 : 1;
            $data['receipt_number'] = 'RD-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

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

    public function show(Donation $donation): View
    {
        $donation->load('donor');
        return view('donations.show', compact('donation'));
    }

    public function edit(Donation $donation): View
    {
        return view('donations.edit', compact('donation'));
    }

    public function update(Request $request, Donation $donation): RedirectResponse
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

        $donation->update($data);

        return redirect()->route('donations.index')->with('status', 'Don mis à jour avec succès.');
    }

    public function destroy(Donation $donation): RedirectResponse
    {
        $donation->delete();

        return redirect()->route('donations.index')->with('status', 'Don supprimé avec succès.');
    }

    public function generateReceipt(Donation $donation): View
    {
        $donation->load('donor');
        $amountInWords = $this->numberToWords($donation->amount);

        return view('donations.receipt', compact('donation', 'amountInWords'));
    }

    public function downloadReceipt(Donation $donation): Response
    {
        $donation->load('donor');
        $amountInWords = $this->numberToWords($donation->amount);

        $pdf = Pdf::loadView('donations.receipt-pdf', compact('donation', 'amountInWords'));

        $filename = 'recu_fiscal_' . $donation->receipt_number . '.pdf';

        return $pdf->download($filename);
    }

    public function emailReceipt(Donation $donation): RedirectResponse
    {
        $donation->load('donor');

        if (!$donation->donor->email) {
            return back()->with('error', 'Aucun email renseigné pour ce donateur.');
        }

        $amountInWords = $this->numberToWords($donation->amount);
        $pdf = Pdf::loadView('donations.receipt-pdf', compact('donation', 'amountInWords'));
        $pdfContent = $pdf->output();

        try {
            Mail::to($donation->donor->email)
                ->send(new DonationReceiptMail($donation, $pdfContent));

            // Mark receipt as sent
            $donation->update(['is_receipt_sent' => true]);

            return back()->with('status', 'Reçu fiscal envoyé par email à ' . $donation->donor->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    private function numberToWords($number): string
    {
        $units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
        $teens = ['dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];
        $tens = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix'];

        $intPart = floor($number);
        $decPart = round(($number - $intPart) * 100);

        if ($intPart == 0) {
            return 'zéro euro' . ($decPart > 0 ? ' et ' . $decPart . ' centimes' : 's');
        }

        $result = '';

        if ($intPart >= 100) {
            $hundreds = floor($intPart / 100);
            if ($hundreds == 1) {
                $result .= 'cent ';
            } else {
                $result .= $units[$hundreds] . ' cent' . ($hundreds > 1 && $intPart % 100 == 0 ? 's ' : ' ');
            }
            $intPart %= 100;
        }

        if ($intPart >= 20) {
            $tensDigit = floor($intPart / 10);
            $unitsDigit = $intPart % 10;

            if ($tensDigit == 7 || $tensDigit == 9) {
                $result .= $tens[$tensDigit - 1] . '-' . $teens[$unitsDigit];
            } else {
                $result .= $tens[$tensDigit];
                if ($unitsDigit > 0) {
                    $result .= ($tensDigit == 8 ? '-' : '-') . $units[$unitsDigit];
                }
            }
        } elseif ($intPart >= 10) {
            $result .= $teens[$intPart - 10];
        } elseif ($intPart > 0) {
            $result .= $units[$intPart];
        }

        $result .= ' euro' . (floor($number) > 1 ? 's' : '');

        if ($decPart > 0) {
            $result .= ' et ' . $decPart . ' centime' . ($decPart > 1 ? 's' : '');
        }

        return trim($result);
    }
}
