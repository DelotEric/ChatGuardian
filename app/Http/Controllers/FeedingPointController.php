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
        $this->authorizeRoles(['admin', 'benevole']);

        $feedingPoints = FeedingPoint::with('volunteers')->latest()->get();
        $volunteers = Volunteer::query()->orderBy('first_name')->get();

        return view('feeding_points.index', compact('feedingPoints', 'volunteers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles('admin');

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

    public function update(Request $request, FeedingPoint $feedingPoint): RedirectResponse
    {
        $this->authorizeRoles('admin');

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
        $this->authorizeRoles('admin');

        $feedingPoint->volunteers()->detach();
        $feedingPoint->delete();

        return redirect()->route('feeding-points.index')->with('status', 'Point de nourrissage supprimé.');
    }
}
