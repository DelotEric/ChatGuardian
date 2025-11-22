<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatPhoto;
use App\Models\CatStay;
use App\Models\FosterFamily;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CatController extends Controller
{
    public function index(): View
    {
        $this->authorizeRoles(['admin', 'benevole', 'famille']);

        $cats = Cat::query()->with('currentStay')->latest()->paginate(10);

        return view('cats.index', compact('cats'));
    }

    public function show(Cat $cat): View
    {
        $this->authorizeRoles(['admin', 'benevole', 'famille']);

        $cat->load(['photos', 'stays.fosterFamily', 'vetRecords', 'adoption']);
        $families = FosterFamily::query()->where('is_active', true)->orderBy('name')->get();

        return view('cats.show', compact('cat', 'families'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:male,female,unknown'],
            'birthdate' => ['nullable', 'date'],
            'status' => ['required', 'in:free,foster,adopted,deceased'],
            'sterilized' => ['sometimes', 'boolean'],
            'sterilized_at' => ['nullable', 'date'],
            'vaccinated' => ['sometimes', 'boolean'],
            'vaccinated_at' => ['nullable', 'date'],
            'fiv_status' => ['required', 'in:unknown,positive,negative'],
            'felv_status' => ['required', 'in:unknown,positive,negative'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['sterilized'] = $request->boolean('sterilized');
        $data['vaccinated'] = $request->boolean('vaccinated');

        Cat::create($data);

        return redirect()->route('cats.index')->with('status', 'Chat créé.');
    }

    public function update(Request $request, Cat $cat): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:male,female,unknown'],
            'birthdate' => ['nullable', 'date'],
            'status' => ['required', 'in:free,foster,adopted,deceased'],
            'sterilized' => ['sometimes', 'boolean'],
            'sterilized_at' => ['nullable', 'date'],
            'vaccinated' => ['sometimes', 'boolean'],
            'vaccinated_at' => ['nullable', 'date'],
            'fiv_status' => ['required', 'in:unknown,positive,negative'],
            'felv_status' => ['required', 'in:unknown,positive,negative'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['sterilized'] = $request->boolean('sterilized');
        $data['vaccinated'] = $request->boolean('vaccinated');

        $cat->update($data);

        return back()->with('status', 'Profil du chat mis à jour.');
    }

    public function storeStay(Request $request, Cat $cat): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $data = $request->validate([
            'foster_family_id' => ['required', 'exists:foster_families,id'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'outcome' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'next_status' => ['nullable', 'in:free,foster,adopted,deceased'],
        ]);

        $cat->stays()->create($data);

        $cat->update([
            'status' => $data['next_status'] ?? 'foster',
        ]);

        return back()->with('status', 'Séjour enregistré.');
    }

    public function closeStay(Request $request, Cat $cat, CatStay $stay): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);
        abort_unless($stay->cat_id === $cat->id, 404);

        $data = $request->validate([
            'ended_at' => ['required', 'date', 'after_or_equal:' . $stay->started_at->format('Y-m-d')],
            'outcome' => ['nullable', 'string', 'max:255'],
            'next_status' => ['nullable', 'in:free,foster,adopted,deceased'],
        ]);

        $stay->update($data);

        if ($data['next_status'] ?? false) {
            $cat->update(['status' => $data['next_status']]);
        }

        return back()->with('status', 'Séjour clôturé.');
    }

    public function storePhotos(Request $request, Cat $cat): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $request->validate([
            'photos' => ['required', 'array'],
            'photos.*' => ['image', 'max:3072'],
            'captions' => ['array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
        ]);

        $remainingSlots = max(0, 3 - $cat->photos()->count());
        $files = array_slice($request->file('photos', []), 0, $remainingSlots);

        if (empty($files)) {
            return back()->with('error', 'Nombre maximum de photos atteint (3 par chat).');
        }

        foreach ($files as $index => $file) {
            $path = $file->store('cats', 'public');
            $caption = $request->input("captions.$index");

            $cat->photos()->create([
                'path' => $path,
                'caption' => $caption,
            ]);
        }

        return back()->with('status', 'Photo(s) ajoutée(s) au profil du chat.');
    }

    public function destroyPhoto(Cat $cat, CatPhoto $photo): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        abort_unless($photo->cat_id === $cat->id, 404);

        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }
        $photo->delete();

        return back()->with('status', 'Photo supprimée.');
    }
}
