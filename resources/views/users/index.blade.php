@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des utilisateurs</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouvel utilisateur
    </a>
</div>

<!-- Recherche -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="row g-3">
            <div class="col-md-10">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Rechercher par nom ou email..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Rechercher</button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Email vérifié</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-info ms-2">Vous</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">Administrateur</span>
                                    @elseif($user->role === 'manager')
                                        <span class="badge bg-primary">Gestionnaire</span>
                                    @else
                                        <span class="badge bg-secondary">Utilisateur</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-warning">Non</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                            Voir
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            Modifier
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <p class="mb-0">Aucun utilisateur trouvé.</p>
            </div>
        @endif
    </div>
</div>
@endsection

