<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatAdoption;
use App\Models\CatVetRecord;
use App\Models\Donation;
use App\Models\FeedingPoint;
use App\Models\FosterFamily;
use App\Models\ActivityLog;
use App\Models\StockItem;
use App\Models\Volunteer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $catTotals = Cat::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $metrics = [
            'cats' => Cat::count(),
            'families' => FosterFamily::where('is_active', true)->count(),
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
            'stock_items' => StockItem::count(),
            'stock_low' => StockItem::whereColumn('quantity', '<=', 'restock_threshold')->count(),
        ];

        $recentCats = Cat::latest()->take(6)->get();
        $recentAdoptions = CatAdoption::with('cat')->latest('adopted_at')->take(5)->get();
        $recentDonations = Donation::with('donor')->latest('donated_at')->take(6)->get();
        $recentVetRecords = CatVetRecord::with('cat')->latest('visit_date')->take(5)->get();
        $activities = ActivityLog::with('user')->latest()->take(8)->get();

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

        return view('dashboard', [
            'metrics' => $metrics,
            'catTotals' => $catTotals,
            'recentCats' => $recentCats,
            'recentAdoptions' => $recentAdoptions,
            'recentDonations' => $recentDonations,
            'recentVetRecords' => $recentVetRecords,
            'activities' => $activities,
            'donationChart' => $donationChart,
            'organization' => $this->organization(),
        ]);
    }
}
