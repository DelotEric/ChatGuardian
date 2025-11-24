<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatController extends Controller
{
    public function index(): View
    {
        $cats = Cat::query()->with('currentStay')->latest()->paginate(10);

        return view('cats.index', compact('cats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:male,female,unknown'],
            'birthdate' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'sterilized' => ['sometimes', 'boolean'],
            'sterilized_at' => ['nullable', 'date'],
            'vaccinated' => ['sometimes', 'boolean'],
            'vaccinated_at' => ['nullable', 'date'],
            'fiv_status' => ['required', 'in:unknown,positive,negative'],
            'felv_status' => ['required', 'in:unknown,positive,negative'],
            'notes' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'gallery_photos.*' => ['nullable', 'image', 'max:2048'],
            'adopter_id' => ['nullable', 'exists:adopters,id'],
            'adopted_at' => ['nullable', 'date'],
        ]);

        $data['sterilized'] = $request->boolean('sterilized');
        $data['vaccinated'] = $request->boolean('vaccinated');

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('cats', 'public');
            $data['photo_path'] = $path;
        }

        $cat = Cat::create($data);

        if ($request->hasFile('gallery_photos')) {
            foreach ($request->file('gallery_photos') as $photo) {
                $path = $photo->store('cats/gallery', 'public');
                $cat->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('cats.index')->with('status', 'Chat créé.');
    }
    public function show(Cat $cat): View
    {
        $cat->load('currentStay.fosterFamily');
        return view('cats.show', compact('cat'));
    }

    public function edit(Cat $cat): View
    {
        return view('cats.edit', compact('cat'));
    }

    public function update(Request $request, Cat $cat): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:male,female,unknown'],
            'birthdate' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'sterilized' => ['sometimes', 'boolean'],
            'sterilized_at' => ['nullable', 'date'],
            'vaccinated' => ['sometimes', 'boolean'],
            'vaccinated_at' => ['nullable', 'date'],
            'fiv_status' => ['required', 'in:unknown,positive,negative'],
            'felv_status' => ['required', 'in:unknown,positive,negative'],
            'notes' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'gallery_photos.*' => ['nullable', 'image', 'max:2048'],
            'delete_gallery_photos' => ['array'],
            'delete_gallery_photos.*' => ['exists:cat_photos,id'],
            'adopter_id' => ['nullable', 'exists:adopters,id'],
            'adopted_at' => ['nullable', 'date'],
        ]);

        $data['sterilized'] = $request->boolean('sterilized');
        $data['vaccinated'] = $request->boolean('vaccinated');

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($cat->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($cat->photo_path);
            }
            $path = $request->file('photo')->store('cats', 'public');
            $data['photo_path'] = $path;
        }

        $cat->update($data);

        if ($request->hasFile('gallery_photos')) {
            foreach ($request->file('gallery_photos') as $photo) {
                $path = $photo->store('cats/gallery', 'public');
                $cat->photos()->create(['path' => $path]);
            }
        }

        if ($request->filled('delete_gallery_photos')) {
            $photosToDelete = $cat->photos()->whereIn('id', $request->delete_gallery_photos)->get();
            foreach ($photosToDelete as $photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
        }

        return redirect()->route('cats.index')->with('status', 'Chat mis à jour avec succès.');
    }

    public function destroy(Cat $cat): RedirectResponse
    {
        $cat->delete();

        return redirect()->route('cats.index')->with('status', 'Chat supprimé avec succès.');
    }

    public function medicalHistory(Cat $cat): View
    {
        $cat->load([
            'medicalCares' => function ($query) {
                $query->with('partner')->orderBy('care_date', 'desc');
            },
            'weightRecords' => function ($query) {
                $query->orderBy('measured_at', 'desc');
            }
        ]);

        // Statistiques rapides
        $stats = [
            'total_cares' => $cat->medicalCares->count(),
            'last_vaccination' => $cat->medicalCares()
                ->where('type', 'vaccination')
                ->where('status', 'completed')
                ->latest('care_date')
                ->first(),
            'next_care' => $cat->medicalCares()
                ->where('status', 'scheduled')
                ->where('care_date', '>=', now())
                ->orderBy('care_date')
                ->first(),
            'latest_weight' => $cat->latest_weight,
        ];

        return view('cats.medical-history', compact('cat', 'stats'));
    }

    public function generateHealthRecord(Cat $cat): View
    {
        $cat->load([
            'medicalCares' => function ($query) {
                $query->with('partner')->orderBy('care_date', 'desc');
            },
            'weightRecords' => function ($query) {
                $query->orderBy('measured_at', 'asc');
            },
            'photos'
        ]);

        return view('cats.health-record', compact('cat'));
    }

    public function downloadHealthRecord(Cat $cat)
    {
        $cat->load([
            'medicalCares' => function ($query) {
                $query->with('partner')->orderBy('care_date', 'desc');
            },
            'weightRecords' => function ($query) {
                $query->orderBy('measured_at', 'asc');
            },
            'photos'
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cats.health-record-pdf', compact('cat'));

        $filename = 'carnet_sante_' . \Str::slug($cat->name) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
