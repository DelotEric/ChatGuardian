<?php

namespace App\Http\Controllers;

use App\Models\Adopter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdopterController extends Controller
{
    public function index(): View
    {
        $adopters = Adopter::withCount('cats')->latest()->paginate(10);

        return view('adopters.index', compact('adopters'));
    }

    public function create(): View
    {
        return view('adopters.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:adopters'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        Adopter::create($data);

        return redirect()->route('adopters.index')->with('status', 'Adoptant ajouté avec succès.');
    }

    public function show(Adopter $adopter): View
    {
        $adopter->load('cats');
        return view('adopters.show', compact('adopter'));
    }

    public function edit(Adopter $adopter): View
    {
        return view('adopters.edit', compact('adopter'));
    }

    public function update(Request $request, Adopter $adopter): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:adopters,email,' . $adopter->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        $adopter->update($data);

        return redirect()->route('adopters.index')->with('status', 'Adoptant mis à jour avec succès.');
    }

    public function destroy(Adopter $adopter): RedirectResponse
    {
        if ($adopter->cats()->count() > 0) {
            return redirect()->route('adopters.index')->with('error', 'Impossible de supprimer cet adoptant car il a des chats associés.');
        }

        $adopter->delete();

        return redirect()->route('adopters.index')->with('status', 'Adoptant supprimé avec succès.');
    }
}
