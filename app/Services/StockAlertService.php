<?php

namespace App\Services;

use App\Mail\StockAlertMail;
use App\Models\Organization;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class StockAlertService
{
    public function lowItems(): Collection
    {
        return StockItem::query()
            ->whereColumn('quantity', '<=', 'restock_threshold')
            ->orderBy('name')
            ->get();
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
     * @return array{int,int} [low_items_count, sent_recipients_count]
     */
    public function send(): array
    {
        $items = $this->lowItems();

        if ($items->isEmpty()) {
            return [0, 0];
        }

        $recipients = $this->recipients();

        if ($recipients->isEmpty()) {
            return [$items->count(), 0];
        }

        $organization = $this->organization();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->send(new StockAlertMail($items, $recipient, $organization));
        }

        return [$items->count(), $recipients->count()];
    }
}
