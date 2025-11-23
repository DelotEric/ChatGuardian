@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('volunteers.index') }}"
                    class="text-decoration-none text-muted">Bénévoles</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $volunteer->full_name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('volunteers.edit', $volunteer) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('volunteers.destroy', $volunteer) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bénévole ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 100px; height: 100px;">
                        <i class="bi bi-person fs-1 text-secondary"></i>
                    </div>
                    <h3 class="h5">{{ $volunteer->full_name }}</h3>
                    <span
                        class="badge {{ $volunteer->is_active ? 'bg-soft-success text-success' : 'bg-soft-secondary text-muted' }} mb-2">
                        {{ $volunteer->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                    <p class="text-muted small mb-0">
                        Inscrit {{ $volunteer->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Coordonnées</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <i class="bi bi-envelope text-muted me-2"></i>
                        <a href="mailto:{{ $volunteer->email }}"
                            class="text-decoration-none text-dark">{{ $volunteer->email }}</a>
                    </li>
                    @if($volunteer->phone)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-telephone text-muted me-2"></i>
                            <a href="tel:{{ $volunteer->phone }}"
                                class="text-decoration-none text-dark">{{ $volunteer->phone }}</a>
                        </li>
                    @endif
                    @if($volunteer->city)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-geo-alt text-muted me-2"></i>
                            <span>{{ $volunteer->city }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Informations</div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted small text-uppercase">Disponibilités</h6>
                        <p>{{ $volunteer->availability ?: 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <h6 class="text-muted small text-uppercase">Compétences & Notes</h6>
                        <p class="mb-0">{{ $volunteer->skills ?: 'Aucune compétence spécifiée.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection