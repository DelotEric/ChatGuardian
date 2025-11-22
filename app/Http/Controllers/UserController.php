<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorizeRoles(['admin']);

        $users = User::query()->orderBy('name')->paginate(12);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'volunteers' => User::where('role', 'benevole')->count(),
            'families' => User::where('role', 'famille')->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles(['admin']);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'benevole', 'famille'])],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('users.index')->with('status', 'Utilisateur créé.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeRoles(['admin']);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'benevole', 'famille'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return back()->with('status', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeRoles(['admin']);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return back()->with('status', 'Utilisateur supprimé.');
    }
}
