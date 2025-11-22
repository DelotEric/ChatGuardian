<?php

namespace App\Http\Controllers;

use App\Models\CatReminder;
use App\Models\CatVetRecord;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        $events = $this->collectEvents();

        return view('calendar.index', [
            'events' => $events,
        ]);
    }

    public function exportIcs(): Response
    {
        $events = $this->collectEvents();

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//ChatGuardian//Calendar//FR',
            'CALSCALE:GREGORIAN',
        ];

        foreach ($events as $event) {
            $start = Carbon::parse($event['start']);
            $uid = md5($event['title'].$event['start'].$event['extendedProps']['type']);
            $description = $event['extendedProps']['type'] === 'vet'
                ? 'Visite vétérinaire'
                : 'Rappel';
            if (!empty($event['extendedProps']['cat'])) {
                $description .= ' — Chat : '.$event['extendedProps']['cat'];
            }
            if (!empty($event['extendedProps']['reason'])) {
                $description .= ' — Motif : '.$event['extendedProps']['reason'];
            }
            if (!empty($event['extendedProps']['status'])) {
                $description .= ' — Statut : '.$event['extendedProps']['status'];
            }

            $lines = array_merge($lines, [
                'BEGIN:VEVENT',
                'UID:'.$uid.'@chatguardian',
                'SUMMARY:'.$this->escapeIcsText($event['title']),
                'DESCRIPTION:'.$this->escapeIcsText($description),
                'DTSTART;VALUE=DATE:'.$start->format('Ymd'),
                'DTEND;VALUE=DATE:'.$start->copy()->addDay()->format('Ymd'),
                'DTSTAMP:'.Carbon::now()->utc()->format('Ymd\THis\Z'),
                'END:VEVENT',
            ]);
        }

        $lines[] = 'END:VCALENDAR';

        $body = implode("\r\n", $lines);

        return response($body, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="chatguardian-agenda.ics"',
        ]);
    }

    private function collectEvents()
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

        return $reminderEvents
            ->concat($vetEvents)
            ->filter(fn ($event) => !empty($event['start']))
            ->values();
    }

    private function escapeIcsText(string $text): string
    {
        return str_replace(['\\', ';', ',', "\n"], ['\\\\', '\\;', '\\,', '\\n'], $text);
    }
}
