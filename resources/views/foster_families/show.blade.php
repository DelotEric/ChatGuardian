@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('foster-families.index') }}"
                    class="text-decoration-none text-muted">Familles d'accueil</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $fosterFamily->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('foster-families.edit', $fosterFamily) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('foster-families.destroy', $fosterFamily) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette famille ?');">
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
                        <i class="bi bi-house-heart fs-1 text-secondary"></i>
                    </div>
                    <h3 class="h5">{{ $fosterFamily->name }}</h3>
                    <span
                        class="badge {{ $fosterFamily->is_active ? 'bg-soft-success text-success' : 'bg-soft-secondary text-muted' }} mb-2">
                        {{ $fosterFamily->is_active ? 'Active' : 'Suspendue' }}
                    </span>
                    <p class="text-muted small mb-0">
                        Capacité : {{ $fosterFamily->capacity }} chat(s)
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Coordonnées</div>
                <ul class="list-group list-group-flush">
                    @if($fosterFamily->email)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-envelope text-muted me-2"></i>
                            <a href="mailto:{{ $fosterFamily->email }}"
                                class="text-decoration-none text-dark">{{ $fosterFamily->email }}</a>
                        </li>
                    @endif
                    @if($fosterFamily->phone)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-telephone text-muted me-2"></i>
                            <a href="tel:{{ $fosterFamily->phone }}"
                                class="text-decoration-none text-dark">{{ $fosterFamily->phone }}</a>
                        </li>
                    @endif
                    @if($fosterFamily->address || $fosterFamily->city)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-geo-alt text-muted me-2"></i>
                            <span>{{ $fosterFamily->address }} {{ $fosterFamily->city }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Préférences & Notes</div>
                <div class="card-body">
                    <p class="mb-0">{{ $fosterFamily->preferences ?: 'Aucune préférence spécifiée.' }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Historique des accueils</span>
                    <span class="badge bg-secondary">{{ $fosterFamily->stays->count() }} séjours</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($fosterFamily->stays as $stay)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $stay->cat->name }}</h6>
                                    <small class="text-muted">
                                        @if($stay->start_date)
                                            Du {{ $stay->start_date->format('d/m/Y') }}
                                            @if($stay->end_date)
                                                au {{ $stay->end_date->format('d/m/Y') }}
                                            @else
                                                (en cours)
                                            @endif
                                        @else
                                            Date non renseignée
                                        @endif
                                    </small>
                                </div>
                                @if(!$stay->end_date)
                                    <span class="badge bg-success">Actuel</span>
                                @else
                                    <span class="badge bg-light text-dark">Terminé</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-3">
                            Aucun chat accueilli pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection