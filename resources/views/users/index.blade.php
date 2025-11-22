@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Utilisateurs</h1>
            <p class="text-muted mb-0">Gestion des comptes et rôles (admin seulement).</p>
        </div>
        <form class="d-flex" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="me-2">
                <label class="form-label small text-muted mb-1" for="user-name">Nom</label>
                <input id="user-name" class="form-control" type="text" name="name" required>
            </div>
            <div class="me-2">
                <label class="form-label small text-muted mb-1" for="user-email">Email</label>
                <input id="user-email" class="form-control" type="email" name="email" required>
            </div>
            <div class="me-2">
                <label class="form-label small text-muted mb-1" for="user-role">Rôle</label>
                <select id="user-role" class="form-select" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="benevole">Bénévole</option>
                    <option value="famille">Famille d'accueil</option>
                </select>
            </div>
            <div class="me-2">
                <label class="form-label small text-muted mb-1" for="user-password">Mot de passe</label>
                <input id="user-password" class="form-control" type="password" name="password" required>
            </div>
            <div class="align-self-end">
                <button class="btn btn-primary" type="submit">Créer</button>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total comptes</p>
                    <p class="h4 mb-0">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Admins</p>
                    <p class="h4 mb-0">{{ $stats['admins'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Bénévoles</p>
                    <p class="h4 mb-0">{{ $stats['volunteers'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Familles</p>
                    <p class="h4 mb-0">{{ $stats['families'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Liste des utilisateurs</h2>
                <span class="text-muted small">{{ $users->total() }} entrées</span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @if ($user->role === 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @elseif ($user->role === 'benevole')
                                        <span class="badge bg-success">Bénévole</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Famille</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#editUser{{ $user->id }}">Éditer</button>
                                    <form class="d-inline" action="{{ route('users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ce compte ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit"
                                            @if (auth()->id() === $user->id) disabled @endif>Supprimer</button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1"
                                aria-labelledby="editUser{{ $user->id }}Label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUser{{ $user->id }}Label">Modifier l'utilisateur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('users.update', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label" for="edit-name-{{ $user->id }}">Nom</label>
                                                    <input id="edit-name-{{ $user->id }}" class="form-control" type="text"
                                                        name="name" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="edit-email-{{ $user->id }}">Email</label>
                                                    <input id="edit-email-{{ $user->id }}" class="form-control" type="email"
                                                        name="email" value="{{ $user->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="edit-role-{{ $user->id }}">Rôle</label>
                                                    <select id="edit-role-{{ $user->id }}" class="form-select" name="role" required>
                                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                                        <option value="benevole" @selected($user->role === 'benevole')>Bénévole</option>
                                                        <option value="famille" @selected($user->role === 'famille')>Famille d'accueil</option>
                                                    </select>
                                                </div>
                                                <div class="mb-1">
                                                    <label class="form-label" for="edit-password-{{ $user->id }}">Nouveau mot de passe (optionnel)</label>
                                                    <input id="edit-password-{{ $user->id }}" class="form-control" type="password"
                                                        name="password" placeholder="Laisser vide pour conserver l'existant" minlength="8">
                                                    <small class="text-muted">8 caractères minimum</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
@endsection
