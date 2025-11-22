<?php

namespace App\Http\Controllers;

use App\Models\FosterFamily;
use App\Models\CatAdoption;
use App\Models\Cat;
use App\Models\Donation;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function fosterContract(FosterFamily $family): Response
    {
        $this->authorizeRoles('admin');

        $pdf = Pdf::loadView('pdf.foster_contract', [
            'family' => $family,
            'today' => now()->format('d/m/Y'),
            'organization' => $this->organization(),
        ])->setPaper('a4');

        return $pdf->download("contrat-famille-{$family->id}.pdf");
    }

    public function donationReceipt(Donation $donation): Response
    {
        $this->authorizeRoles('admin');

        $pdf = Pdf::loadView('pdf.donation_receipt', [
            'donation' => $donation->load('donor'),
            'today' => now()->format('d/m/Y'),
            'organization' => $this->organization(),
        ])->setPaper('a4');

        $number = $donation->receipt_number ?: 'recu-' . $donation->id;

        return $pdf->download("recu-fiscal-{$number}.pdf");
    }

    public function adoptionContract(Cat $cat, CatAdoption $adoption): Response
    {
        $this->authorizeRoles('admin');

        abort_unless($adoption->cat_id === $cat->id, 404);
        $adoption->load('cat');

        $pdf = Pdf::loadView('pdf.adoption_contract', [
            'adoption' => $adoption,
            'today' => now()->format('d/m/Y'),
            'organization' => $this->organization(),
        ])->setPaper('a4');

        return $pdf->download("contrat-adoption-{$adoption->id}.pdf");
    }
}
