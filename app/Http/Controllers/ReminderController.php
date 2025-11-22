<?php

namespace App\Http\Controllers;

use App\Models\CatReminder;
use App\Services\ReminderDigestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function __construct(private ReminderDigestService $digestService)
    {
    }

    public function sendDigest(Request $request): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $request->validate([
            'range' => ['required', 'in:today,week,overdue'],
        ]);

        $range = $request->string('range')->toString();

        [$reminderCount, $recipientCount] = $this->digestService->send($range);

        if ($reminderCount === 0) {
            return back()->with('status', 'Aucun rappel à envoyer pour la période sélectionnée.');
        }

        if ($recipientCount === 0) {
            return back()->with('status', 'Aucun utilisateur admin/bénévole avec un email pour envoyer le récap.');
        }

        return back()->with('status', 'Email récapitulatif envoyé aux administrateurs et bénévoles.');
    }

    public function index(Request $request): View
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $request->validate([
            'status' => ['nullable', 'in:pending,done,all'],
            'range' => ['nullable', 'in:upcoming,overdue,week,all'],
            'type' => ['nullable', 'in:vet,vaccine,adoption_followup,health,admin,other'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $status = $request->input('status', 'pending');
        $range = $request->input('range', 'upcoming');
        $type = $request->input('type');
        $queryText = $request->input('q');

        $query = CatReminder::query()
            ->with(['cat', 'user']);

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'done') {
            $query->where('status', 'done');
        }

        $today = Carbon::today();

        if ($range === 'overdue') {
            $query->where('status', 'pending')->whereDate('due_date', '<', $today);
        } elseif ($range === 'week') {
            $query->where('status', 'pending')->whereBetween('due_date', [$today, $today->copy()->addDays(7)]);
        } elseif ($range === 'upcoming') {
            $query->whereDate('due_date', '>=', $today);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($queryText) {
            $query->where(function ($inner) use ($queryText) {
                $inner->where('title', 'like', "%{$queryText}%")
                    ->orWhereHas('cat', function ($catQuery) use ($queryText) {
                        $catQuery->where('name', 'like', "%{$queryText}%");
                    });
            });
        }

        $reminders = $query
            ->orderBy('due_date')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'overdue' => CatReminder::where('status', 'pending')->whereDate('due_date', '<', $today)->count(),
            'week' => CatReminder::where('status', 'pending')->whereBetween('due_date', [$today, $today->copy()->addDays(7)])->count(),
            'month' => CatReminder::where('status', 'pending')->whereBetween('due_date', [$today, $today->copy()->addDays(30)])->count(),
            'done_month' => CatReminder::where('status', 'done')->whereBetween('completed_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])->count(),
        ];

        return view('reminders.index', compact('reminders', 'stats', 'status', 'range', 'type', 'queryText'));
    }
}
