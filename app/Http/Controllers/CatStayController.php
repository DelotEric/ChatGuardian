<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatStay;
use App\Models\FosterFamily;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatStayController extends Controller
{
    public function index(): View
    {
        $stays = CatStay::query()
            ->with(['cat', 'fosterFamily'])
            ->latest('started_at')
            ->paginate(10);

        return view('cat_stays.index', compact('stays'));
    }

    public function create(): View
    {
        // On ne propose que les chats qui ne sont pas actuellement en séjour
        $cats = Cat::query()
            ->whereDoesntHave('currentStay')
            ->orderBy('name')
            ->get();

        $families = FosterFamily::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('cat_stays.create', compact('cats', 'families'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cat_id' => ['required', 'exists:cats,id'],
            'foster_family_id' => ['required', 'exists:foster_families,id'],
            'started_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        // Vérifier si le chat est déjà en séjour
        $cat = Cat::findOrFail($data['cat_id']);
        if ($cat->currentStay) {
            return back()->withErrors(['cat_id' => 'Ce chat est déjà en famille d\'accueil.']);
        }

        CatStay::create($data);

        return redirect()->route('cat-stays.index')->with('status', 'Séjour créé avec succès.');
    }

    public function show(CatStay $catStay): View
    {
        $catStay->load(['cat', 'fosterFamily']);
        return view('cat_stays.show', compact('catStay'));
    }

    public function edit(CatStay $catStay): View
    {
        return view('cat_stays.edit', compact('catStay'));
    }

    public function update(Request $request, CatStay $catStay): RedirectResponse
    {
        $data = $request->validate([
            'started_at' => ['required', 'date'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'outcome' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $catStay->update($data);

        return redirect()->route('cat-stays.index')->with('status', 'Séjour mis à jour.');
    }

    public function destroy(CatStay $catStay): RedirectResponse
    {
        $catStay->delete();

        return redirect()->route('cat-stays.index')->with('status', 'Séjour supprimé.');
    }
}
