<?php

namespace App\Http\Controllers;

use App\Models\FosterFamily;
use App\Models\Donation;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function fosterContract(FosterFamily $family): Response
    {
        $pdf = Pdf::loadView('pdf.foster_contract', [
            'family' => $family,
            'today' => now()->format('d/m/Y'),
        ])->setPaper('a4');

        return $pdf->download("contrat-famille-{$family->id}.pdf");
    }

    public function donationReceipt(Donation $donation): Response
    {
        $pdf = Pdf::loadView('pdf.donation_receipt', [
            'donation' => $donation->load('donor'),
            'today' => now()->format('d/m/Y'),
        ])->setPaper('a4');

        $number = $donation->receipt_number ?: 'recu-' . $donation->id;

        return $pdf->download("recu-fiscal-{$number}.pdf");
    }
}
