@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('partners.index') }}"
                    class="text-decoration-none text-muted">Partenaires</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $partner->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('partners.edit', $partner) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('partners.destroy', $partner) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?');">
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
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Type</span>
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
                </div>
                <ul class="list-group list-group-flush">
                    @if($partner->contact_person)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Contact</small>
                            {{ $partner->contact_person }}
                        </li>
                    @endif
                    @if($partner->email)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Email</small>
                            <a href="mailto:{{ $partner->email }}">{{ $partner->email }}</a>
                        </li>
                    @endif
                    @if($partner->phone)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Téléphone</small>
                            <a href="tel:{{ $partner->phone }}">{{ $partner->phone }}</a>
                        </li>
                    @endif
                    @if($partner->website)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Site web</small>
                            <a href="{{ $partner->website }}" target="_blank">{{ $partner->website }}</a>
                        </li>
                    @endif
                    @if($partner->address)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Adresse</small>
                            {{ $partner->address }}
                        </li>
                    @endif
                    @if($partner->city)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Ville</small>
                            {{ $partner->city }}
                        </li>
                    @endif
                    @if($partner->discount_rate)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Remise</small>
                            <span class="badge bg-success">{{ $partner->discount_rate }}%</span>
                        </li>
                    @endif
                    <li class="list-group-item">
                        <small class="text-muted d-block">Statut</small>
                        @if($partner->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            @if($partner->services)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Services offerts</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $partner->services }}</p>
                    </div>
                </div>
            @endif

            @if($partner->notes)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Notes</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $partner->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection