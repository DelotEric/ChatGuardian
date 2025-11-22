<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatReminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatReminderController extends Controller
{
    public function store(Request $request, Cat $cat): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:vet,vaccine,adoption_followup,health,admin,other'],
            'due_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $reminder = $cat->reminders()->create(array_merge($data, [
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]));

        $this->logActivity('reminder.created', $cat, 'Rappel ajouté : ' . $reminder->title);

        return back()->with('status', 'Rappel ajouté.');
    }

    public function update(Request $request, Cat $cat, CatReminder $reminder): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);
        abort_unless($reminder->cat_id === $cat->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:vet,vaccine,adoption_followup,health,admin,other'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,done'],
            'notes' => ['nullable', 'string'],
        ]);

        $reminder->update(array_merge($data, [
            'completed_at' => $data['status'] === 'done' ? ($reminder->completed_at ?? now()) : null,
        ]));

        $this->logActivity('reminder.updated', $cat, 'Rappel mis à jour : ' . $reminder->title);

        return back()->with('status', 'Rappel mis à jour.');
    }

    public function complete(Cat $cat, CatReminder $reminder): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);
        abort_unless($reminder->cat_id === $cat->id, 404);

        $reminder->update([
            'status' => 'done',
            'completed_at' => now(),
        ]);

        $this->logActivity('reminder.completed', $cat, 'Rappel clôturé : ' . $reminder->title);

        return back()->with('status', 'Rappel marqué comme fait.');
    }

    public function destroy(Cat $cat, CatReminder $reminder): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);
        abort_unless($reminder->cat_id === $cat->id, 404);

        $reminder->delete();

        $this->logActivity('reminder.deleted', $cat, 'Rappel supprimé : ' . $reminder->title);

        return back()->with('status', 'Rappel supprimé.');
    }
}
