<?php

namespace App\Http\Controllers;

use App\Models\FosterFamily;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FosterFamilyController extends Controller
{
    public function index(): View
    {
        $families = FosterFamily::query()->withCount('stays')->latest()->paginate(10);

        return view('foster_families.index', compact('families'));
    }

    public function store(Request $request): RedirectResponse
    {
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
}
