<?php

namespace App\View\Composers;

use App\Models\Event;
use App\Models\InventoryItem;
use App\Models\MedicalCare;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        // 1. Stock faible
        $lowStockItems = InventoryItem::whereColumn('quantity', '<=', 'min_quantity')->get();

        // 2. Soins médicaux (en retard ou aujourd'hui)
        $medicalAlerts = MedicalCare::where('status', 'scheduled')
            ->whereDate('care_date', '<=', now())
            ->orderBy('care_date')
            ->get();

        // 3. Événements aujourd'hui
        $todayEvents = Event::where('is_active', true)
            ->whereDate('event_date', now())
            ->get();

        $totalNotifications = $lowStockItems->count() + $medicalAlerts->count() + $todayEvents->count();

        $view->with('globalNotifications', [
            'lowStock' => $lowStockItems,
            'medical' => $medicalAlerts,
            'events' => $todayEvents,
            'count' => $totalNotifications,
        ]);
    }
}
