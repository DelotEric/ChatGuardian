<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function edit(): View
    {
        $this->authorizeRoles('admin');

        $organization = $this->organization();

        return view('settings.organization', compact('organization'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'legal_name' => ['nullable', 'string', 'max:190'],
            'siret' => ['nullable', 'string', 'max:190'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:190'],
            'address' => ['nullable', 'string', 'max:190'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'iban' => ['nullable', 'string', 'max:190'],
            'bic' => ['nullable', 'string', 'max:190'],
            'website' => ['nullable', 'string', 'max:190'],
        ]);

        $organization = $this->organization();
        $organization->update($data);

        return redirect()->route('settings.organization')->with('status', 'Profil association mis à jour.');
    }
}
