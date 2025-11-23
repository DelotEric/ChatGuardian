@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Détails de l'utilisateur</h1>
    <div>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary me-2">Modifier</a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informations</h5>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Nom</th>
                        <td><strong>{{ $user->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Rôle</th>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Administrateur</span>
                            @elseif($user->role === 'manager')
                                <span class="badge bg-primary">Gestionnaire</span>
                            @else
                                <span class="badge bg-secondary">Utilisateur</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Email vérifié</th>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Oui - {{ $user->email_verified_at->format('d/m/Y à H:i') }}</span>
                            @else
                                <span class="badge bg-warning">Non</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Créé le</th>
                        <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Modifié le</th>
                        <td>{{ $user->updated_at->format('d/m/Y à H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Modifier</a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Supprimer</button>
                        </form>
                    @else
                        <button class="btn btn-danger w-100" disabled title="Vous ne pouvez pas supprimer votre propre compte">Supprimer</button>
                    @endif
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

