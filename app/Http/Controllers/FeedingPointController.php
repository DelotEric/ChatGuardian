<?php

namespace App\Http\Controllers;

use App\Models\FeedingPoint;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function export(): StreamedResponse
    {
        $this->authorizeRoles('admin');

        $filename = 'points_nourrissage_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Nom', 'Latitude', 'Longitude', 'Description', 'Bénévoles associés']);

            FeedingPoint::with('volunteers')->orderBy('name')->chunk(200, function ($points) use ($handle) {
                foreach ($points as $point) {
                    fputcsv($handle, [
                        $point->name,
                        $point->latitude,
                        $point->longitude,
                        $point->description,
                        $point->volunteers->pluck('full_name')->implode('; '),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
