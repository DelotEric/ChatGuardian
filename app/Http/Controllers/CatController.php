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
        $this->authorizeRoles(['admin', 'benevole', 'famille']);

        $cats = Cat::query()->with('currentStay')->latest()->paginate(10);

        return view('cats.index', compact('cats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

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
        ]);

        $data['sterilized'] = $request->boolean('sterilized');
        $data['vaccinated'] = $request->boolean('vaccinated');

        Cat::create($data);

        return redirect()->route('cats.index')->with('status', 'Chat créé.');
    }
}
