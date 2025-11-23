<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\MedicalCare;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalCareController extends Controller
{
    public function index(Request $request): View
    {
        $query = MedicalCare::with(['cat', 'partner']);

        if ($request->filled('cat_id')) {
            $query->where('cat_id', $request->cat_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('upcoming')) {
            $query->where('status', 'scheduled')
                ->where('care_date', '<=', now()->addDays(30));
        }

        $medicalCares = $query->latest('care_date')->paginate(15);
        $cats = Cat::orderBy('name')->get();

        return view('medical_cares.index', compact('medicalCares', 'cats'));
    }

    public function create(Request $request): View
    {
        $cats = Cat::orderBy('name')->get();
        $partners = Partner::where('type', 'veterinarian')->where('is_active', true)->orderBy('name')->get();
        $selectedCatId = $request->get('cat_id');

        $fosterFamilies = \App\Models\FosterFamily::where('is_active', true)->orderBy('name')->get();
        $volunteers = \App\Models\Volunteer::where('is_active', true)->orderBy('first_name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        $adopters = \App\Models\Adopter::orderBy('name')->get();

        return view('medical_cares.create', compact('cats', 'partners', 'selectedCatId', 'fosterFamilies', 'volunteers', 'users', 'adopters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cat_id' => ['required', 'exists:cats,id'],
            'type' => ['required', 'in:vaccination,deworming,vet_visit,sterilization,other'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'care_date' => ['required', 'date'],
            'next_due_date' => ['nullable', 'date', 'after:care_date'],
            'status' => ['required', 'in:scheduled,completed,cancelled'],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'responsible_type' => ['nullable', 'string', 'in:App\Models\FosterFamily,App\Models\Volunteer,App\Models\User,App\Models\Adopter'],
            'responsible_id' => ['nullable', 'integer'],
        ]);

        MedicalCare::create($data);

        return redirect()->route('medical-cares.index')->with('status', 'Soin médical ajouté avec succès.');
    }

    public function show(MedicalCare $medicalCare): View
    {
        $medicalCare->load(['cat', 'partner']);
        return view('medical_cares.show', compact('medicalCare'));
    }

    public function edit(MedicalCare $medicalCare): View
    {
        $cats = Cat::orderBy('name')->get();
        $partners = Partner::where('type', 'veterinarian')->where('is_active', true)->orderBy('name')->get();

        $fosterFamilies = \App\Models\FosterFamily::where('is_active', true)->orderBy('name')->get();
        $volunteers = \App\Models\Volunteer::where('is_active', true)->orderBy('first_name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        $adopters = \App\Models\Adopter::orderBy('name')->get();

        return view('medical_cares.edit', compact('medicalCare', 'cats', 'partners', 'fosterFamilies', 'volunteers', 'users', 'adopters'));
    }

    public function update(Request $request, MedicalCare $medicalCare): RedirectResponse
    {
        $data = $request->validate([
            'cat_id' => ['required', 'exists:cats,id'],
            'type' => ['required', 'in:vaccination,deworming,vet_visit,sterilization,other'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'care_date' => ['required', 'date'],
            'next_due_date' => ['nullable', 'date', 'after:care_date'],
            'status' => ['required', 'in:scheduled,completed,cancelled'],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'responsible_type' => ['nullable', 'string', 'in:App\Models\FosterFamily,App\Models\Volunteer,App\Models\User,App\Models\Adopter'],
            'responsible_id' => ['nullable', 'integer'],
        ]);

        $medicalCare->update($data);

        return redirect()->route('medical-cares.index')->with('status', 'Soin médical mis à jour avec succès.');
    }

    public function destroy(MedicalCare $medicalCare): RedirectResponse
    {
        $medicalCare->delete();

        return redirect()->route('medical-cares.index')->with('status', 'Soin médical supprimé avec succès.');
    }

    public function sendAlert(MedicalCare $medicalCare): RedirectResponse
    {
        $medicalCare->load(['responsible', 'cat', 'partner']);

        if (!$medicalCare->responsible) {
            return redirect()->back()->with('error', 'Aucun responsable assigné pour ce soin.');
        }

        $email = null;
        if ($medicalCare->responsible_type === 'App\Models\FosterFamily') {
            $email = $medicalCare->responsible->email;
        } elseif ($medicalCare->responsible_type === 'App\Models\Volunteer') {
            $email = $medicalCare->responsible->email;
        } elseif ($medicalCare->responsible_type === 'App\Models\User') {
            $email = $medicalCare->responsible->email;
        } elseif ($medicalCare->responsible_type === 'App\Models\Adopter') {
            $email = $medicalCare->responsible->email;
        }

        if (!$email) {
            return redirect()->back()->with('error', 'Le responsable n\'a pas d\'adresse email.');
        }

        try {
            \Mail::to($email)->send(new \App\Mail\MedicalCareAlert($medicalCare));
            return redirect()->back()->with('status', 'Email d\'alerte envoyé avec succès à ' . $email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }
}
