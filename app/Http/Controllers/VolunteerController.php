<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function index(): View
    {
        $volunteers = Volunteer::query()->latest()->paginate(10);

        return view('volunteers.index', compact('volunteers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:volunteers'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:120'],
            'availability' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Volunteer::create($data);

        return redirect()->route('volunteers.index')->with('status', 'Bénévole ajouté avec succès.');
    }
    public function show(Volunteer $volunteer): View
    {
        return view('volunteers.show', compact('volunteer'));
    }

    public function edit(Volunteer $volunteer): View
    {
        return view('volunteers.edit', compact('volunteer'));
    }

    public function update(Request $request, Volunteer $volunteer): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:volunteers,email,' . $volunteer->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:120'],
            'availability' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $volunteer->update($data);

        return redirect()->route('volunteers.index')->with('status', 'Bénévole mis à jour avec succès.');
    }

    public function destroy(Volunteer $volunteer): RedirectResponse
    {
        $volunteer->delete();

        return redirect()->route('volunteers.index')->with('status', 'Bénévole supprimé avec succès.');
    }
}
