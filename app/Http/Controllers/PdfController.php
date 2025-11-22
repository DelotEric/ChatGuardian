<?php

namespace App\Http\Controllers;

use App\Models\FosterFamily;
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
}
