@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('cats.index') }}" class="text-decoration-none text-muted">Chats</a>
                / Détails</p>
            <h1 class="h3 fw-bold">{{ $cat->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cats.medical-history', $cat) }}" class="btn btn-info">
                <i class="bi bi-clipboard2-pulse"></i> Historique médical
            </a>
            <a href="{{ route('cats.edit', $cat) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('cats.destroy', $cat) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chat ?');">
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
                    <div class="mb-3">
                        @if($cat->photo_path)
                            <img src="{{ Storage::url($cat->photo_path) }}" class="rounded-circle img-thumbnail"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px;">
                                <i class="bi bi-cat fs-1 text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="h5">{{ $cat->name }}</h3>
                    <span class="badge bg-soft-primary text-primary mb-2">{{ ucfirst($cat->status) }}</span>
                    <p class="text-muted small mb-0">
                        Ajouté {{ $cat->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Informations médicales</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Stérilisé
                        @if($cat->sterilized)
                            <span class="badge bg-success">Oui</span>
                        @else
                            <span class="badge bg-warning text-dark">Non</span>
                        @endif
                    </li>
                    @if($cat->sterilized_at)
                        <li class="list-group-item d-flex justify-content-between align-items-center text-muted small">
                            Date stérilisation
                            <span>{{ $cat->sterilized_at->format('d/m/Y') }}</span>
                        </li>
                    @endif

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Vacciné
                        @if($cat->vaccinated)
                            <span class="badge bg-success">Oui</span>
                        @else
                            <span class="badge bg-warning text-dark">Non</span>
                        @endif
                    </li>
                    @if($cat->vaccinated_at)
                        <li class="list-group-item d-flex justify-content-between align-items-center text-muted small">
                            Date vaccination
                            <span>{{ $cat->vaccinated_at->format('d/m/Y') }}</span>
                        </li>
                    @endif

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        FIV
                        <span
                            class="badge {{ $cat->fiv_status === 'positive' ? 'bg-danger' : ($cat->fiv_status === 'negative' ? 'bg-success' : 'bg-secondary') }}">
                            {{ ucfirst($cat->fiv_status) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        FELV
                        <span
                            class="badge {{ $cat->felv_status === 'positive' ? 'bg-danger' : ($cat->felv_status === 'negative' ? 'bg-success' : 'bg-secondary') }}">
                            {{ ucfirst($cat->felv_status) }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Détails</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="text-muted small">Sexe</label>
                            <p class="fw-semibold">
                                {{ $cat->sex === 'male' ? 'Mâle' : ($cat->sex === 'female' ? 'Femelle' : 'Inconnu') }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small">Date de naissance</label>
                            <p class="fw-semibold">
                                {{ $cat->birthdate ? $cat->birthdate->format('d/m/Y') : 'Non renseignée' }}
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0">{{ $cat->notes ?: 'Aucune note.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Situation actuelle</span>
                    @if($cat->currentStay)
                        <span class="badge bg-info text-dark">En famille d'accueil</span>
                    @else
                        <span class="badge bg-secondary">Non placé</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($cat->currentStay)
                        <p class="mb-1"><strong>Famille :</strong> {{ $cat->currentStay->fosterFamily->name }}</p>
                        <p class="mb-0 text-muted">Depuis le {{ $cat->currentStay->started_at->format('d/m/Y') }}</p>
                    @else
                        <p class="text-muted mb-0">Ce chat n'est actuellement pas en famille d'accueil.</p>
                    @endif
                </div>
            </div>

            @if($cat->adopter)
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                        <span>Adoptant</span>
                        <span class="badge bg-success">Adopté</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Nom :</strong>
                            <a href="{{ route('adopters.show', $cat->adopter) }}" class="text-decoration-none">
                                {{ $cat->adopter->name }}
                            </a>
                        </p>
                        @if($cat->adopted_at)
                            <p class="mb-0 text-muted">Adopté le {{ $cat->adopted_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if($cat->photos->count() > 0)
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white fw-bold">Galerie photos</div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($cat->photos as $photo)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <a href="{{ Storage::url($photo->path) }}" target="_blank">
                                        <img src="{{ Storage::url($photo->path) }}" class="img-fluid rounded"
                                            style="height: 150px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection