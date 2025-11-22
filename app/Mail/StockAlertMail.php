<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class StockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $items,
        public User $recipient,
        public Organization $organization,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Alerte stocks faibles — ChatGuardian')
            ->view('emails.stock_alert');
    }
}
