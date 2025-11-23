@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold">Partenaires</h1>
            <p class="text-muted mb-0">Gestion des partenaires (vétérinaires, animaleries, etc.)</p>
        </div>
        <a href="{{ route('partners.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouveau partenaire
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les types</option>
                        <option value="veterinarian" {{ request('type') == 'veterinarian' ? 'selected' : '' }}>Vétérinaire</option>
                        <option value="pet_store" {{ request('type') == 'pet_store' ? 'selected' : '' }}>Animalerie</option>
                        <option value="shelter" {{ request('type') == 'shelter' ? 'selected' : '' }}>Refuge</option>
                        <option value="supplier" {{ request('type') == 'supplier' ? 'selected' : '' }}>Fournisseur</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="active_only" id="active_only" 
                            {{ request()->has('active_only') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label" for="active_only">
                            Actifs uniquement
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Contact</th>
                        <th>Ville</th>
                        <th>Statut</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $partner)
                        <tr>
                            <td>
                                <a href="{{ route('partners.show', $partner) }}" class="text-decoration-none text-dark fw-semibold">
                                    {{ $partner->name }}
                                </a>
                            </td>
                            <td>
                                @php
                                    $typeLabels = [
                                        'veterinarian' => 'Vétérinaire',
                                        'pet_store' => 'Animalerie',
                                        'shelter' => 'Refuge',
                                        'supplier' => 'Fournisseur',
                                        'other' => 'Autre'
                                    ];
                                    $typeColors = [
                                        'veterinarian' => 'primary',
                                        'pet_store' => 'info',
                                        'shelter' => 'warning',
                                        'supplier' => 'secondary',
                                        'other' => 'light'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $typeColors[$partner->type] }}">
                                    {{ $typeLabels[$partner->type] }}
                                </span>
                            </td>
                            <td>{{ $partner->email ?: $partner->phone ?: '-' }}</td>
                            <td>{{ $partner->city ?: '-' }}</td>
                            <td>
                                @if($partner->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('partners.show', $partner) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('partners.edit', $partner) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('partners.destroy', $partner) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucun partenaire enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $partners->links() }}
    </div>
@endsection
