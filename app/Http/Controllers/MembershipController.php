<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Mail\TaxReceiptMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\Response;

class MembershipController extends Controller
{
    public function index(Request $request): View
    {
        $query = Membership::with('member');

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $memberships = $query->latest('year')->latest('payment_date')->paginate(20);
        $members = Member::orderBy('last_name')->get();

        return view('memberships.index', compact('memberships', 'members'));
    }

    public function create(Request $request): View
    {
        $members = Member::orderBy('last_name')->get();
        $selectedMemberId = $request->get('member_id');

        return view('memberships.create', compact('members', 'selectedMemberId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,check,transfer,card,other'],
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'receipt_issued' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        Membership::create($data);

        return redirect()->route('members.show', $data['member_id'])
            ->with('status', 'Cotisation enregistrée avec succès.');
    }

    public function show(Membership $membership): View
    {
        $membership->load('member');
        return view('memberships.show', compact('membership'));
    }

    public function edit(Membership $membership): View
    {
        $members = Member::orderBy('last_name')->get();
        return view('memberships.edit', compact('membership', 'members'));
    }

    public function update(Request $request, Membership $membership): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,check,transfer,card,other'],
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'receipt_issued' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $membership->update($data);

        return redirect()->route('members.show', $membership->member_id)->with('status', 'Adhésion mise à jour avec succès.');
    }

    public function destroy(Membership $membership): RedirectResponse
    {
        $memberId = $membership->member_id;
        $membership->delete();

        return redirect()->route('members.show', $memberId)->with('status', 'Adhésion supprimée avec succès.');
    }

    public function generateReceipt(Membership $membership): View
    {
        $membership->load('member');

        // Convertir le montant en lettres (simple version française)
        $amountInWords = $this->numberToWords($membership->amount);

        return view('memberships.receipt', compact('membership', 'amountInWords'));
    }

    public function downloadReceipt(Membership $membership): Response
    {
        $membership->load('member');
        $amountInWords = $this->numberToWords($membership->amount);

        $pdf = Pdf::loadView('memberships.receipt-pdf', compact('membership', 'amountInWords'));

        $filename = 'recu_fiscal_' . $membership->receipt_number . '.pdf';

        return $pdf->download($filename);
    }

    public function emailReceipt(Membership $membership): RedirectResponse
    {
        $membership->load('member');

        if (!$membership->member->email) {
            return back()->with('error', 'Aucun email renseigné pour cet adhérent.');
        }

        $amountInWords = $this->numberToWords($membership->amount);
        $pdf = Pdf::loadView('memberships.receipt-pdf', compact('membership', 'amountInWords'));
        $pdfContent = $pdf->output();

        try {
            Mail::to($membership->member->email)
                ->send(new TaxReceiptMail($membership, $pdfContent));

            return back()->with('status', 'Reçu fiscal envoyé par email à ' . $membership->member->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    private function numberToWords($number): string
    {
        $units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
        $teens = ['dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];
        $tens = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix'];

        $integer = floor($number);
        $decimal = round(($number - $integer) * 100);

        $words = '';

        if ($integer >= 100) {
            $hundreds = floor($integer / 100);
            $words = $hundreds > 1 ? $units[$hundreds] . ' cent' : 'cent';
            if ($hundreds > 1 && $integer % 100 > 0)
                $words .= ' ';
            $integer %= 100;
        }

        if ($integer >= 20) {
            $tensDigit = floor($integer / 10);
            $words .= $tens[$tensDigit];
            $integer %= 10;
            if ($integer > 0)
                $words .= '-' . $units[$integer];
        } elseif ($integer >= 10) {
            $words .= $teens[$integer - 10];
            $integer = 0;
        } elseif ($integer > 0) {
            $words .= $units[$integer];
        }

        $words .= ' euro' . ($integer > 1 ? 's' : '');

        if ($decimal > 0) {
            $words .= ' et ' . $decimal . ' centime' . ($decimal > 1 ? 's' : '');
        }

        return ucfirst(trim($words));
    }
}
