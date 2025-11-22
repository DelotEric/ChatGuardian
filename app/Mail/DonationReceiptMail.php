<?php

namespace App\Mail;

use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function build(): self
    {
        $pdf = Pdf::loadView('pdf.donation_receipt', [
            'donation' => $this->donation,
            'today' => now()->format('d/m/Y'),
        ])->setPaper('a4');

        return $this
            ->subject('Votre reçu fiscal - ChatGuardian')
            ->view('emails.donation_receipt')
            ->with([
                'donation' => $this->donation,
            ])
            ->attachData($pdf->output(), "recu-fiscal-{$this->donation->receipt_number}.pdf");
    }
}
