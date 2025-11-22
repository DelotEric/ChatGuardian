<?php

namespace App\Services;

use App\Mail\ReminderDigestMail;
use App\Models\CatReminder;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class ReminderDigestService
{
    /**
     * Collect pending reminders for the requested range.
     */
    public function fetchReminders(string $range): Collection
    {
        $today = Carbon::today();

        $query = CatReminder::with(['cat'])
            ->where('status', 'pending');

        if ($range === 'today') {
            $query->whereDate('due_date', $today);
        } elseif ($range === 'overdue') {
            $query->whereDate('due_date', '<', $today);
        } else {
            $query->whereBetween('due_date', [$today, $today->copy()->addDays(7)]);
        }

        return $query->orderBy('due_date')->get();
    }

    public function recipients(): Collection
    {
        return User::query()
            ->whereIn('role', ['admin', 'benevole'])
            ->whereNotNull('email')
            ->get();
    }

    public function organization(): Organization
    {
        return Organization::query()->first() ?? Organization::create(Organization::defaults());
    }

    /**
     * Send the digest email to all recipients and return counts.
     */
    public function send(string $range): array
    {
        $reminders = $this->fetchReminders($range);

        if ($reminders->isEmpty()) {
            return [0, 0];
        }

        $recipients = $this->recipients();

        if ($recipients->isEmpty()) {
            return [$reminders->count(), 0];
        }

        $organization = $this->organization();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->send(new ReminderDigestMail($reminders, $recipient, $organization));
        }

        return [$reminders->count(), $recipients->count()];
    }
}
