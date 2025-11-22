@extends('layouts.app')

@section('content')
@php $role = auth()->user()->role ?? null; @endphp
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-muted mb-1">Accueil temporaire</p>
        <h1 class="h4 fw-bold">Familles d'accueil</h1>
    </div>
    @if($role === 'admin')
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#familyForm">Ajouter</button>
    @endif
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($role === 'admin')
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
@endif

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
                        <th>Contrat</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
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
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('foster-families.contract', $family) }}">
                                    PDF
                                </a>
                            </td>
                            <td>
                                <span class="badge {{ $family->is_active ? 'bg-soft-success text-success' : 'bg-soft-secondary text-muted' }}">
                                    {{ $family->is_active ? 'Active' : 'Suspendue' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($role === 'admin')
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editFamily{{ $family->id }}">Modifier</button>
                                        <form method="POST" action="{{ route('foster-families.destroy', $family) }}" onsubmit="return confirm('Supprimer cette famille ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <div class="modal fade" id="editFamily{{ $family->id }}" tabindex="-1" aria-labelledby="editFamilyLabel{{ $family->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editFamilyLabel{{ $family->id }}">Modifier {{ $family->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form class="row g-3 px-3 pb-3" method="POST" action="{{ route('foster-families.update', $family) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Nom de la famille</label>
                                            <input name="name" type="text" class="form-control" value="{{ $family->name }}" required>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Email</label>
                                            <input name="email" type="email" class="form-control" value="{{ $family->email }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Téléphone</label>
                                            <input name="phone" type="text" class="form-control" value="{{ $family->phone }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Capacité</label>
                                            <input name="capacity" type="number" class="form-control" min="1" value="{{ $family->capacity }}" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Adresse</label>
                                            <input name="address" type="text" class="form-control" value="{{ $family->address }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Ville</label>
                                            <input name="city" type="text" class="form-control" value="{{ $family->city }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Préférences</label>
                                            <textarea name="preferences" class="form-control" rows="2">{{ $family->preferences }}</textarea>
                                        </div>
                                        <div class="col-12 form-check mb-3">
                                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="family_active_{{ $family->id }}" {{ $family->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="family_active_{{ $family->id }}">Active</label>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button class="btn btn-primary" type="submit">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucune famille enregistrée.</td>
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
