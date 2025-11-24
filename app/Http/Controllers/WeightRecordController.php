<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\WeightRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeightRecordController extends Controller
{
    public function store(Request $request, Cat $cat): RedirectResponse
    {
        $data = $request->validate([
            'weight' => ['required', 'numeric', 'min:0', 'max:50'],
            'measured_at' => ['required', 'date'],
            'measured_by' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['cat_id'] = $cat->id;

        WeightRecord::create($data);

        return redirect()
            ->route('cats.medical-history', $cat)
            ->with('status', 'Pesée ajoutée avec succès.');
    }

    public function update(Request $request, Cat $cat, WeightRecord $weightRecord): RedirectResponse
    {
        $data = $request->validate([
            'weight' => ['required', 'numeric', 'min:0', 'max:50'],
            'measured_at' => ['required', 'date'],
            'measured_by' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $weightRecord->update($data);

        return redirect()
            ->route('cats.medical-history', $cat)
            ->with('status', 'Pesée modifiée avec succès.');
    }

    public function destroy(Cat $cat, WeightRecord $weightRecord): RedirectResponse
    {
        $weightRecord->delete();

        return redirect()
            ->route('cats.medical-history', $cat)
            ->with('status', 'Pesée supprimée avec succès.');
    }
}
