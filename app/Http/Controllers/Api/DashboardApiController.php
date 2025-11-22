<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Cat;
use App\Models\CatAdoption;
use App\Models\CatReminder;
use App\Models\CatVetRecord;
use App\Models\Donation;
use App\Models\FeedingPoint;
use App\Models\FosterFamily;
use App\Models\StockItem;
use App\Models\Volunteer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function summary(): JsonResponse
    {
        $catTotals = Cat::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $metrics = [
            'cats' => Cat::count(),
            'families_active' => FosterFamily::where('is_active', true)->count(),
            'volunteers' => Volunteer::count(),
            'donations_month' => Donation::whereMonth('donated_at', now()->month)
                ->whereYear('donated_at', now()->year)
                ->sum('amount'),
            'adoptions_month' => CatAdoption::whereMonth('adopted_at', now()->month)
                ->whereYear('adopted_at', now()->year)
                ->count(),
            'vet_month' => CatVetRecord::whereMonth('visit_date', now()->month)
                ->whereYear('visit_date', now()->year)
                ->sum('amount'),
            'vet_visits_month' => CatVetRecord::whereMonth('visit_date', now()->month)
                ->whereYear('visit_date', now()->year)
                ->count(),
            'feeding_points' => FeedingPoint::count(),
            'stock_low' => StockItem::whereColumn('quantity', '<=', 'restock_threshold')->count(),
        ];

        $activities = ActivityLog::with('user:id,name,role')->latest()->take(10)->get();

        $months = collect(range(5, 0))->map(fn ($i) => Date::now()->startOfMonth()->subMonths($i));
        $donationsByMonth = Donation::selectRaw('DATE_FORMAT(donated_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('donated_at', '>=', $months->first()->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month');

        $donationChart = $months->map(function ($month) use ($donationsByMonth) {
            $key = $month->format('Y-m');

            return [
                'label' => $month->locale('fr')->isoFormat('MMM YY'),
                'value' => round($donationsByMonth[$key] ?? 0, 2),
            ];
        });

        $upcomingReminders = CatReminder::with('cat:id,name')
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->take(6)
            ->get();

        return response()->json([
            'metrics' => $metrics,
            'cat_totals' => $catTotals,
            'donation_chart' => $donationChart,
            'recent_activities' => $activities,
            'upcoming_reminders' => $upcomingReminders,
        ]);
    }
}
