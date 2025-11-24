<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Donation;
use App\Models\FosterFamily;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistiques Chats
        $totalCats = Cat::count();
        $catsForAdoption = Cat::where('status', 'A l\'adoption')->count();
        $catsInFoster = Cat::has('currentStay')->count();

        // Statistiques Bénévoles
        $totalVolunteers = Volunteer::count();
        $activeVolunteers = Volunteer::where('is_active', true)->count();

        // Statistiques Familles d'accueil
        $totalFamilies = FosterFamily::count();
        $activeFamilies = FosterFamily::where('is_active', true)->count();
        $availableFamilies = FosterFamily::where('is_active', true)
            ->withCount([
                'stays' => function ($query) {
                    $query->whereNull('ended_at');
                }
            ])
            ->get()
            ->filter(function ($family) {
                return $family->stays_count < $family->capacity;
            })
            ->count();

        // Calcul du taux d'occupation
        $totalCapacity = FosterFamily::where('is_active', true)->sum('capacity');
        $currentOccupancy = \App\Models\CatStay::whereNull('ended_at')->count();
        $occupancyRate = $totalCapacity > 0 ? round(($currentOccupancy / $totalCapacity) * 100, 1) : 0;
        $availableSpots = max(0, $totalCapacity - $currentOccupancy);

        // Statistiques Dons (Mois en cours)
        $donationsThisMonth = Donation::whereMonth('donated_at', now()->month)
            ->whereYear('donated_at', now()->year)
            ->sum('amount');

        // Activité récente
        $latestCats = Cat::latest()->take(5)->get();
        $latestDonations = Donation::with('donor')->latest('donated_at')->take(5)->get();

        // Soins médicaux - Alertes
        $overdueCare = \App\Models\MedicalCare::with(['cat', 'partner'])
            ->where('status', 'scheduled')
            ->where('care_date', '<', now())
            ->orderBy('care_date')
            ->get();

        $upcomingCareWeek = \App\Models\MedicalCare::with(['cat', 'partner'])
            ->where('status', 'scheduled')
            ->whereBetween('care_date', [now(), now()->addDays(7)])
            ->orderBy('care_date')
            ->get();

        $upcomingCareLater = \App\Models\MedicalCare::with(['cat', 'partner'])
            ->where('status', 'scheduled')
            ->whereBetween('care_date', [now()->addDays(8), now()->addDays(30)])
            ->orderBy('care_date')
            ->get();

        // Inventaire - Stock faible
        $lowStockItems = \App\Models\InventoryItem::whereColumn('quantity', '<=', 'min_quantity')
            ->orderBy('name')
            ->get();

        // Actualités récentes
        $recentNews = \App\Models\News::recent(4)->get();

        // Événements à venir
        $upcomingEvents = \App\Models\Event::upcoming(3)->get();

        // Compteur d'urgences
        $urgentItemsCount = $overdueCare->count() + $upcomingCareWeek->count() + $lowStockItems->count();

        return view('dashboard', compact(
            'totalCats',
            'catsForAdoption',
            'catsInFoster',
            'totalVolunteers',
            'activeVolunteers',
            'totalFamilies',
            'activeFamilies',
            'availableFamilies',
            'totalCapacity',
            'currentOccupancy',
            'occupancyRate',
            'availableSpots',
            'donationsThisMonth',
            'latestCats',
            'latestDonations',
            'overdueCare',
            'upcomingCareWeek',
            'upcomingCareLater',
            'lowStockItems',
            'urgentItemsCount',
            'recentNews',
            'upcomingEvents'
        ));
    }
}
