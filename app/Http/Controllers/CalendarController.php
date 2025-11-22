<?php

namespace App\Http\Controllers;

use App\Models\CatReminder;
use App\Models\CatVetRecord;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(): View
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $reminderEvents = CatReminder::with('cat')->get()->map(function ($reminder) {
            return [
                'title' => "Rappel : {$reminder->title} ({$reminder->cat?->name})",
                'start' => optional($reminder->due_date)->toDateString(),
                'url' => $reminder->cat ? route('cats.show', $reminder->cat_id) . '#reminders' : null,
                'color' => $reminder->status === 'done' ? '#94a3b8' : '#0ea5e9',
                'extendedProps' => [
                    'type' => 'reminder',
                    'status' => $reminder->status,
                    'cat' => $reminder->cat?->name,
                ],
            ];
        });

        $vetEvents = CatVetRecord::with('cat')->get()->map(function ($record) {
            return [
                'title' => "Visite véto : {$record->cat?->name}",
                'start' => optional($record->visit_date)->toDateString(),
                'url' => $record->cat ? route('cats.show', $record->cat_id) . '#vet-records' : null,
                'color' => '#22c55e',
                'extendedProps' => [
                    'type' => 'vet',
                    'cat' => $record->cat?->name,
                    'reason' => $record->reason,
                ],
            ];
        });

        $events = $reminderEvents
            ->concat($vetEvents)
            ->filter(fn ($event) => !empty($event['start']))
            ->values();

        return view('calendar.index', [
            'events' => $events,
        ]);
    }
}
