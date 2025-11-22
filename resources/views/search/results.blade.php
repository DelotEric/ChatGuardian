@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-3 gap-3">
        <div class="icon-circle bg-primary-subtle text-primary">
            <i class="bi bi-search"></i>
        </div>
        <div>
            <h1 class="h3 mb-1">Recherche</h1>
            <p class="text-muted mb-0">Résultats pour « {{ $query }} »</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary-soft text-primary me-2">Chats</span>
                        <strong>{{ $cats->count() }}</strong> trouvé(s)
                    </div>
                    <a href="{{ route('cats.index') }}" class="small">Voir tous</a>
                </div>
                <div class="card-body">
                    @if($cats->isEmpty())
                        <p class="text-muted mb-0">Aucun chat ne correspond à votre recherche.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($cats as $cat)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">{{ $cat->name }}</div>
                                        <div class="text-muted small">Statut : {{ ucfirst($cat->status) }} — Sexe : {{ ucfirst($cat->sex) }}</div>
                                    </div>
                                    <a href="{{ route('cats.show', $cat) }}" class="btn btn-outline-primary btn-sm">Ouvrir</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success-soft text-success me-2">Familles d'accueil</span>
                        <strong>{{ $families->count() }}</strong> trouvé(es)
                    </div>
                    <a href="{{ route('foster-families.index') }}" class="small">Voir tous</a>
                </div>
                <div class="card-body">
                    @if($families->isEmpty())
                        <p class="text-muted mb-0">Aucune famille ne correspond à votre recherche.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($families as $family)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">{{ $family->name }}</div>
                                        <div class="text-muted small">{{ $family->city ?? 'Ville inconnue' }} — Capacité : {{ $family->capacity ?? 'N/A' }}</div>
                                    </div>
                                    <span class="badge {{ $family->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $family->is_active ? 'Active' : 'Inactif' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-info-soft text-info me-2">Bénévoles</span>
                        <strong>{{ $volunteers->count() }}</strong> trouvé(s)
                    </div>
                    <a href="{{ route('volunteers.index') }}" class="small">Voir tous</a>
                </div>
                <div class="card-body">
                    @if($volunteers->isEmpty())
                        <p class="text-muted mb-0">Aucun bénévole ne correspond à votre recherche.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($volunteers as $volunteer)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">{{ $volunteer->full_name }}</div>
                                        <div class="text-muted small">{{ $volunteer->email }} — {{ $volunteer->city ?? 'Ville inconnue' }}</div>
                                    </div>
                                    <span class="badge {{ $volunteer->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $volunteer->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-warning-soft text-warning me-2">Donateurs</span>
                        <strong>{{ $donors->count() }}</strong> trouvé(s)
                    </div>
                    <a href="{{ route('donors.index') }}" class="small">Voir tous</a>
                </div>
                <div class="card-body">
                    @if($donors->isEmpty())
                        <p class="text-muted mb-0">Aucun donateur ne correspond à votre recherche.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($donors as $donor)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">{{ $donor->name }}</div>
                                        <div class="text-muted small">{{ $donor->email ?? 'Email non renseigné' }} — {{ $donor->city ?? 'Ville inconnue' }}</div>
                                    </div>
                                    <a href="{{ route('donors.index') }}#donor-{{ $donor->id }}" class="btn btn-outline-primary btn-sm">Gérer</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
