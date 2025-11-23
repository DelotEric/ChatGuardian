<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
        public string $pdfContent
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reçu fiscal pour votre don - ChatGuardian',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-receipt',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfContent, 'recu_fiscal_' . $this->donation->receipt_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
