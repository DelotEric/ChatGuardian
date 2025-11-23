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
        $donors = Donor::query()->withCount('donations')->latest()->paginate(10);

        return view('donors.index', compact('donors'));
    }

    public function create(): View
    {
        return view('donors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        Donor::create($data);

        return redirect()->route('donors.index')->with('status', 'Donateur ajouté avec succès.');
    }

    public function show(Donor $donor): View
    {
        $donor->load('donations');
        return view('donors.show', compact('donor'));
    }

    public function edit(Donor $donor): View
    {
        return view('donors.edit', compact('donor'));
    }

    public function update(Request $request, Donor $donor): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $donor->update($data);

        return redirect()->route('donors.index')->with('status', 'Donateur mis à jour avec succès.');
    }

    public function destroy(Donor $donor): RedirectResponse
    {
        if ($donor->donations()->exists()) {
            return back()->withErrors(['error' => 'Impossible de supprimer ce donateur car il a des dons associés.']);
        }

        $donor->delete();

        return redirect()->route('donors.index')->with('status', 'Donateur supprimé avec succès.');
    }
}
