<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::with('creator')->latest('event_date')->paginate(15);
        return view('events.index', compact('events'));
    }

    public function create(): View
    {
        return view('events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:adoption_day,training,meeting,other'],
            'is_active' => ['boolean'],
        ]);

        $data['created_by'] = auth()->id();

        Event::create($data);

        return redirect()->route('events.index')->with('status', 'Événement ajouté avec succès.');
    }

    public function show(Event $event): View
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:adoption_day,training,meeting,other'],
            'is_active' => ['boolean'],
        ]);

        $event->update($data);

        return redirect()->route('events.index')->with('status', 'Événement mis à jour avec succès.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('events.index')->with('status', 'Événement supprimé avec succès.');
    }
}
