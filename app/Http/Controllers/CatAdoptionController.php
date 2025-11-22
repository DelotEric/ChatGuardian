<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatAdoption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatAdoptionController extends Controller
{
    public function store(Request $request, Cat $cat): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'adopter_name' => ['required', 'string', 'max:255'],
            'adopter_email' => ['nullable', 'email', 'max:255'],
            'adopter_phone' => ['nullable', 'string', 'max:50'],
            'adopter_address' => ['nullable', 'string', 'max:255'],
            'adopted_at' => ['required', 'date'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['fee'] = $data['fee'] ?? 0;

        $adoption = $cat->adoption()->updateOrCreate([], $data);
        $cat->update(['status' => 'adopted']);

        return back()->with('status', 'Adoption enregistrée.');
    }

    public function update(Request $request, Cat $cat, CatAdoption $adoption): RedirectResponse
    {
        $this->authorizeRoles('admin');
        abort_unless($adoption->cat_id === $cat->id, 404);

        $data = $request->validate([
            'adopter_name' => ['required', 'string', 'max:255'],
            'adopter_email' => ['nullable', 'email', 'max:255'],
            'adopter_phone' => ['nullable', 'string', 'max:50'],
            'adopter_address' => ['nullable', 'string', 'max:255'],
            'adopted_at' => ['required', 'date'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['fee'] = $data['fee'] ?? 0;

        $adoption->update($data);
        $cat->update(['status' => 'adopted']);

        return back()->with('status', 'Adoption mise à jour.');
    }

    public function destroy(Cat $cat, CatAdoption $adoption): RedirectResponse
    {
        $this->authorizeRoles('admin');
        abort_unless($adoption->cat_id === $cat->id, 404);

        $adoption->delete();
        $cat->update(['status' => 'free']);

        return back()->with('status', 'Adoption supprimée.');
    }
}
