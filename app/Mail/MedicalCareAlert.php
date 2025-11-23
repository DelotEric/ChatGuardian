<?php

namespace App\Mail;

use App\Models\MedicalCare;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedicalCareAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MedicalCare $medicalCare
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rappel soin médical - ' . $this->medicalCare->cat->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.medical-care-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
