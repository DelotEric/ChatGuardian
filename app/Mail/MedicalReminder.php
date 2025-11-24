<?php

namespace App\Mail;

use App\Models\MedicalCare;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedicalReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MedicalCare $medicalCare,
        public int $daysUntilDue,
        public string $urgencyLevel
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->urgencyLevel) {
            'overdue' => '🚨 URGENT: Soin médical en retard - ' . $this->medicalCare->cat->name,
            'today' => '⚠️ Rappel critique: Soin prévu aujourd\'hui - ' . $this->medicalCare->cat->name,
            'soon' => '📅 Rappel: Soin à venir - ' . $this->medicalCare->cat->name,
            default => 'Rappel soin médical - ' . $this->medicalCare->cat->name,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.medical-reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
