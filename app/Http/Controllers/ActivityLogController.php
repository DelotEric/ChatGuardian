<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $query = ActivityLog::query()->with('user')->latest();

        if ($action = $request->string('action')->trim()->value()) {
            $query->where('action', $action);
        }

        if ($subjectType = $request->string('subject_type')->trim()->value()) {
            $query->where('subject_type', $subjectType);
        }

        if ($userId = $request->integer('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($period = $request->string('period')->value()) {
            $query->when($period === 'today', fn ($q) => $q->whereDate('created_at', now()->toDateString()));
            $query->when($period === '7d', fn ($q) => $q->where('created_at', '>=', now()->subDays(7)));
            $query->when($period === '30d', fn ($q) => $q->where('created_at', '>=', now()->subDays(30)));
        }

        $activities = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => ActivityLog::count(),
            'week' => ActivityLog::where('created_at', '>=', now()->subDays(7))->count(),
            'month' => ActivityLog::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('activities.index', [
            'activities' => $activities,
            'actions' => ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action'),
            'subjectTypes' => ActivityLog::select('subject_type')->distinct()->orderBy('subject_type')->pluck('subject_type'),
            'stats' => $stats,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $query = ActivityLog::query()->with('user')->latest();

        if ($action = $request->string('action')->trim()->value()) {
            $query->where('action', $action);
        }

        if ($subjectType = $request->string('subject_type')->trim()->value()) {
            $query->where('subject_type', $subjectType);
        }

        if ($userId = $request->integer('user_id')) {
            $query->where('user_id', $userId);
        }

        $filename = 'activity_log_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Date', 'Utilisateur', 'Rôle', 'Action', 'Sujet', 'Description']);

            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $log) {
                    fputcsv($handle, [
                        $log->created_at->format('Y-m-d H:i'),
                        optional($log->user)->name,
                        optional($log->user)->role,
                        $log->action,
                        class_basename($log->subject_type) . ' #' . $log->subject_id,
                        $log->description,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
