<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatReminder;
use Illuminate\Http\JsonResponse;

class ReminderApiController extends Controller
{
    public function index(): JsonResponse
    {
        $today = now()->toDateString();
        $week = now()->addWeek()->toDateString();

        $reminders = CatReminder::with('cat:id,name')
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', now()->subMonth())
            ->orderBy('due_date')
            ->get(['id', 'cat_id', 'title', 'type', 'due_date', 'status']);

        $stats = [
            'overdue' => $reminders->where('due_date', '<', $today)->count(),
            'today' => $reminders->where('due_date', $today)->count(),
            'week' => $reminders->whereBetween('due_date', [$today, $week])->count(),
            'total' => $reminders->count(),
        ];

        return response()->json([
            'stats' => $stats,
            'reminders' => $reminders,
        ]);
    }
}
