<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorController extends Controller
{
    public function index(): View
    {
        $this->authorizeRoles('admin');

        $donors = Donor::query()
            ->withCount('donations')
            ->latest()
            ->paginate(10);

        $totalDonors = Donor::count();
        $recentDonors = Donor::query()->latest()->take(5)->get();

        return view('donors.index', compact('donors', 'totalDonors', 'recentDonors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        Donor::create($data);

        return redirect()->route('donors.index')->with('status', 'Donateur ajouté.');
    }

    public function update(Request $request, Donor $donor): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $donor->update($data);

        return redirect()->route('donors.index')->with('status', 'Donateur mis à jour.');
    }

    public function destroy(Donor $donor): RedirectResponse
    {
        $this->authorizeRoles('admin');

        if ($donor->donations()->exists()) {
            return redirect()->route('donors.index')->with('error', 'Impossible de supprimer un donateur ayant des dons.');
        }

        $donor->delete();

        return redirect()->route('donors.index')->with('status', 'Donateur supprimé.');
    }
}
