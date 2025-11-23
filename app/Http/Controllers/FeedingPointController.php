<?php

namespace App\Http\Controllers;

use App\Models\FeedingPoint;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedingPointController extends Controller
{
    public function index(): View
    {
        $feedingPoints = FeedingPoint::with('volunteers')->latest()->get();
        $volunteers = Volunteer::query()->orderBy('first_name')->get();

        return view('feeding_points.index', compact('feedingPoints', 'volunteers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'volunteer_ids' => ['array'],
            'volunteer_ids.*' => ['exists:volunteers,id'],
        ]);

        $feedingPoint = FeedingPoint::create($data);
        $feedingPoint->volunteers()->sync($data['volunteer_ids'] ?? []);

        return redirect()->route('feeding-points.index')->with('status', 'Point de nourrissage ajouté.');
    }
    public function show(FeedingPoint $feedingPoint): View
    {
        $feedingPoint->load('volunteers');
        return view('feeding_points.show', compact('feedingPoint'));
    }

    public function edit(FeedingPoint $feedingPoint): View
    {
        $volunteers = Volunteer::query()->orderBy('first_name')->get();
        return view('feeding_points.edit', compact('feedingPoint', 'volunteers'));
    }

    public function update(Request $request, FeedingPoint $feedingPoint): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'volunteer_ids' => ['array'],
            'volunteer_ids.*' => ['exists:volunteers,id'],
        ]);

        $feedingPoint->update($data);
        $feedingPoint->volunteers()->sync($data['volunteer_ids'] ?? []);

        return redirect()->route('feeding-points.index')->with('status', 'Point de nourrissage mis à jour.');
    }

    public function destroy(FeedingPoint $feedingPoint): RedirectResponse
    {
        $feedingPoint->delete();

        return redirect()->route('feeding-points.index')->with('status', 'Point de nourrissage supprimé.');
    }
}
