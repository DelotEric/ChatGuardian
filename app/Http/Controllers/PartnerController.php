<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Partner::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('active_only')) {
            $query->where('is_active', true);
        }

        $partners = $query->latest()->paginate(10);

        return view('partners.index', compact('partners'));
    }

    public function create(): View
    {
        return view('partners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:veterinarian,pet_store,shelter,supplier,other'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:partners'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'website' => ['nullable', 'url', 'max:255'],
            'services' => ['nullable', 'string'],
            'discount_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Partner::create($data);

        return redirect()->route('partners.index')->with('status', 'Partenaire ajouté avec succès.');
    }

    public function show(Partner $partner): View
    {
        return view('partners.show', compact('partner'));
    }

    public function edit(Partner $partner): View
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:veterinarian,pet_store,shelter,supplier,other'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:partners,email,' . $partner->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'website' => ['nullable', 'url', 'max:255'],
            'services' => ['nullable', 'string'],
            'discount_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $partner->update($data);

        return redirect()->route('partners.index')->with('status', 'Partenaire mis à jour avec succès.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $partner->delete();

        return redirect()->route('partners.index')->with('status', 'Partenaire supprimé avec succès.');
    }
}
