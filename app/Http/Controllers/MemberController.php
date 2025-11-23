<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = Member::withCount('memberships');

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('current_year_valid')) {
            $currentYear = date('Y');
            $query->whereHas('memberships', function ($q) use ($currentYear) {
                $q->where('year', $currentYear);
            });
        }

        $members = $query->latest()->paginate(15);

        return view('members.index', compact('members'));
    }

    public function create(): View
    {
        return view('members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'birth_date' => ['nullable', 'date'],
            'join_date' => ['required', 'date'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        Member::create($data);

        return redirect()->route('members.index')->with('status', 'Adhérent ajouté avec succès.');
    }

    public function show(Member $member): View
    {
        $member->load([
            'memberships' => function ($query) {
                $query->latest('year');
            }
        ]);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member): View
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'birth_date' => ['nullable', 'date'],
            'join_date' => ['required', 'date'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $member->update($data);

        return redirect()->route('members.index')->with('status', 'Adhérent mis à jour avec succès.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')->with('status', 'Adhérent supprimé avec succès.');
    }
}
