@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('cat-stays.index') }}"
                    class="text-decoration-none text-muted">Séjours</a> / Détails</p>
            <h1 class="h3 fw-bold">Séjour de {{ $catStay->cat->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cat-stays.edit', $catStay) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('cat-stays.destroy', $catStay) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce séjour ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold">Informations du séjour</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Période</label>
                        <p class="fw-semibold mb-0">
                            Du {{ $catStay->started_at->format('d/m/Y') }}
                            @if($catStay->ended_at)
                                au {{ $catStay->ended_at->format('d/m/Y') }}
                            @else
                                (en cours)
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Statut</label>
                        <div>
                            @if(!$catStay->ended_at)
                                <span class="badge bg-success">En cours</span>
                            @else
                                <span class="badge bg-secondary">Terminé</span>
                            @endif
                        </div>
                    </div>
                    @if($catStay->outcome)
                        <div class="mb-3">
                            <label class="text-muted small">Issue</label>
                            <p class="fw-semibold mb-0">{{ $catStay->outcome }}</p>
                        </div>
                    @endif
                    @if($catStay->notes)
                        <div class="mb-0">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0">{{ $catStay->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-github fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Le chat</h6>
                        <a href="{{ route('cats.show', $catStay->cat) }}"
                            class="text-decoration-none stretched-link">{{ $catStay->cat->name }}</a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-house-heart fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">La famille d'accueil</h6>
                        <a href="{{ route('foster-families.show', $catStay->fosterFamily) }}"
                            class="text-decoration-none stretched-link">{{ $catStay->fosterFamily->name }}</a>
                        <div class="text-muted small">{{ $catStay->fosterFamily->city }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection