@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-muted mb-1">Accueil temporaire</p>
        <h1 class="h4 fw-bold">Familles d'accueil</h1>
    </div>
    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#familyForm">Ajouter</button>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div id="familyForm" class="card shadow-sm border-0 collapse mb-4">
    <div class="card-body">
        <form class="row g-3" method="POST" action="{{ route('foster-families.store') }}">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Nom de la famille</label>
                <input name="name" type="text" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input name="phone" type="text" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Capacité</label>
                <input name="capacity" type="number" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Adresse</label>
                <input name="address" type="text" class="form-control" placeholder="Rue, code postal, ville">
            </div>
            <div class="col-md-12">
                <label class="form-label">Préférences</label>
                <textarea name="preferences" class="form-control" rows="2" placeholder="Chaton, adulte, sociable..."></textarea>
            </div>
            <div class="col-12 form-check">
                <input class="form-check-input" type="checkbox" value="1" name="is_active" id="family_active" checked>
                <label class="form-check-label" for="family_active">Active</label>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Capacité</th>
                        <th>Ville</th>
                        <th>Chats accueillis</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($families as $family)
                        <tr>
                            <td class="fw-semibold">{{ $family->name }}</td>
                            <td>{{ $family->capacity }}</td>
                            <td>{{ $family->city ?? '—' }}</td>
                            <td>{{ $family->stays_count }}</td>
                            <td>
                                <span class="badge {{ $family->is_active ? 'bg-soft-success text-success' : 'bg-soft-secondary text-muted' }}">
                                    {{ $family->is_active ? 'Active' : 'Suspendue' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucune famille enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $families->links() }}
</div>
@endsection
