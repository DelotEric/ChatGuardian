<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\Cat;
use App\Models\Event;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $featuredCats = Cat::where('status', 'A l\'adoption')
            ->with('photos')
            ->latest()
            ->take(3)
            ->get();

        $latestNews = News::published()->latest('publish_date')->take(3)->get();
        $upcomingEvents = Event::upcoming(3)->get();

        return view('public.home', compact('featuredCats', 'latestNews', 'upcomingEvents'));
    }

    public function cats(Request $request): View
    {
        $query = Cat::where('status', 'A l\'adoption')->with('photos');

        if ($request->filled('age')) {
            // Simple age filter logic if needed
        }

        $cats = $query->latest()->paginate(12);

        return view('public.cats.index', compact('cats'));
    }

    public function showCat(Cat $cat): View
    {
        if ($cat->status !== 'A l\'adoption') {
            abort(404);
        }

        $cat->load('photos');
        return view('public.cats.show', compact('cat'));
    }

    public function apply(Cat $cat = null): View
    {
        if ($cat && $cat->status !== 'A l\'adoption') {
            return redirect()->route('public.cats');
        }

        return view('public.application.create', compact('cat'));
    }

    public function submitApplication(Request $request)
    {
        $data = $request->validate([
            'cat_id' => ['nullable', 'exists:cats,id'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'applicant_email' => ['required', 'email', 'max:255'],
            'applicant_phone' => ['required', 'string', 'max:20'],
            'applicant_address' => ['required', 'string'],
            'housing_type' => ['required', 'in:house,apartment'],
            'has_garden' => ['boolean'],
            'family_composition' => ['required', 'string'],
            'other_pets' => ['nullable', 'string'],
            'motivation' => ['required', 'string'],
        ]);

        $data['has_garden'] = $request->boolean('has_garden');

        AdoptionApplication::create($data);

        return redirect()->route('public.home')->with('status', 'Votre candidature a bien été envoyée ! Nous vous recontacterons bientôt.');
    }
}
