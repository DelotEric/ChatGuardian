<?php

namespace App\Http\Controllers;

use App\Models\FosterFamily;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class FosterFamilyController extends Controller
{
    public function index(): View
    {
        $this->authorizeRoles('admin');

        $families = FosterFamily::query()->withCount('stays')->latest()->paginate(10);

        return view('foster_families.index', compact('families'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'capacity' => ['required', 'integer', 'min:1'],
            'preferences' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        FosterFamily::create($data);

        return redirect()->route('foster-families.index')->with('status', 'Famille d\'accueil ajoutée.');
    }

    public function update(Request $request, FosterFamily $family): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'capacity' => ['required', 'integer', 'min:1'],
            'preferences' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $family->update($data);

        return redirect()->route('foster-families.index')->with('status', 'Famille mise à jour.');
    }

    public function destroy(FosterFamily $family): RedirectResponse
    {
        $this->authorizeRoles('admin');

        if ($family->stays()->exists()) {
            return back()->with('error', 'Impossible de supprimer : la famille possède des séjours enregistrés.');
        }

        $family->delete();

        return redirect()->route('foster-families.index')->with('status', 'Famille supprimée.');
    }

    public function export(): StreamedResponse
    {
        $this->authorizeRoles('admin');

        $filename = 'familles_accueil_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Nom', 'Email', 'Téléphone', 'Adresse', 'Ville', 'Capacité', 'Préférences', 'Statut', 'Séjours enregistrés']);

            FosterFamily::withCount('stays')->orderBy('name')->chunk(200, function ($families) use ($handle) {
                foreach ($families as $family) {
                    fputcsv($handle, [
                        $family->name,
                        $family->email,
                        $family->phone,
                        $family->address,
                        $family->city,
                        $family->capacity,
                        $family->preferences,
                        $family->is_active ? 'Active' : 'Inactive',
                        $family->stays_count,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
