<?php

namespace App\Mail;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class TaxReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Membership $membership,
        public string $pdfContent
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reçu fiscal ' . $this->membership->receipt_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tax-receipt',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfContent, 'recu_fiscal_' . $this->membership->receipt_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
