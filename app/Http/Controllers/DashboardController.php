<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Donation;
use App\Models\FeedingPoint;
use App\Models\FosterFamily;
use App\Models\Volunteer;
use Illuminate\Support\Facades\DB;
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
            'feeding_points' => FeedingPoint::count(),
        ];

        $recentCats = Cat::latest()->take(6)->get();
        $recentDonations = Donation::with('donor')->latest('donated_at')->take(6)->get();

        return view('dashboard', [
            'metrics' => $metrics,
            'catTotals' => $catTotals,
            'recentCats' => $recentCats,
            'recentDonations' => $recentDonations,
        ]);
    }
}
