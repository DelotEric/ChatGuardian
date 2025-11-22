<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Donor;
use App\Models\FosterFamily;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorizeRoles(['admin', 'benevole', 'famille']);

        $query = trim((string) $request->input('q'));
        if ($query === '') {
            return redirect()->back()->with('status', 'Merci de saisir un terme de recherche.');
        }

        $cats = Cat::query()
            ->select('id', 'name', 'status', 'sex')
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->take(10)
            ->get();

        $families = FosterFamily::query()
            ->select('id', 'name', 'city', 'capacity', 'is_active')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->orderBy('name')
            ->take(8)
            ->get();

        $volunteers = Volunteer::query()
            ->select('id', 'first_name', 'last_name', 'email', 'city', 'is_active')
            ->where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orderBy('last_name')
            ->take(8)
            ->get();

        $donors = Donor::query()
            ->select('id', 'name', 'email', 'city')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orderBy('name')
            ->take(8)
            ->get();

        return view('search.results', [
            'query' => $query,
            'cats' => $cats,
            'families' => $families,
            'volunteers' => $volunteers,
            'donors' => $donors,
        ]);
    }
}
