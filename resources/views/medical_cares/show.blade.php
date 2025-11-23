@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('medical-cares.index') }}"
                    class="text-decoration-none text-muted">Soins</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $medicalCare->title }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('medical-cares.edit', $medicalCare) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('medical-cares.destroy', $medicalCare) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce soin ?');">
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
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Informations</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <small class="text-muted d-block">Chat</small>
                        <a href="{{ route('cats.show', $medicalCare->cat) }}">{{ $medicalCare->cat->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted d-block">Type</small>
                        @php
                            $typeLabels = [
                                'vaccination' => 'Vaccination',
                                'deworming' => 'Vermifuge',
                                'vet_visit' => 'Visite vétérinaire',
                                'sterilization' => 'Stérilisation',
                                'other' => 'Autre',
                            ];
                        @endphp
                        {{ $typeLabels[$medicalCare->type] }}
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted d-block">Date du soin</small>
                        {{ $medicalCare->care_date->format('d/m/Y') }}
                    </li>
                    @if($medicalCare->next_due_date)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Prochaine échéance</small>
                            {{ $medicalCare->next_due_date->format('d/m/Y') }}
                        </li>
                    @endif
                    <li class="list-group-item">
                        <small class="text-muted d-block">Statut</small>
                        @if($medicalCare->status === 'completed')
                            <span class="badge bg-success">Effectué</span>
                        @elseif($medicalCare->status === 'cancelled')
                            <span class="badge bg-secondary">Annulé</span>
                        @else
                            <span class="badge bg-primary">Prévu</span>
                        @endif
                    </li>
                    @if($medicalCare->partner)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Vétérinaire</small>
                            <a href="{{ route('partners.show', $medicalCare->partner) }}">{{ $medicalCare->partner->name }}</a>
                        </li>
                    @endif
                    @if($medicalCare->cost)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Coût</small>
                            {{ number_format($medicalCare->cost, 2) }} €
                        </li>
                    @endif
                    @if($medicalCare->responsible)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Responsable</small>
                            @php
                                $responsibleLabel = match ($medicalCare->responsible_type) {
                                    'App\Models\FosterFamily' => 'Famille d\'accueil',
                                    'App\Models\Volunteer' => 'Bénévole',
                                    'App\Models\User' => 'Utilisateur',
                                    'App\Models\Adopter' => 'Adoptant',
                                    default => 'Inconnu'
                                };
                            @endphp
                            <strong>{{ $responsibleLabel }} :</strong> {{ $medicalCare->responsible->name }}
                            @if($medicalCare->responsible->email)
                                <br><small class="text-muted">{{ $medicalCare->responsible->email }}</small>
                            @endif
                        </li>
                    @endif
                </ul>
                @if($medicalCare->status === 'scheduled' && $medicalCare->responsible)
                    <div class="card-footer bg-white">
                        <form action="{{ route('medical-cares.send-alert', $medicalCare) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-envelope"></i> Envoyer alerte email
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-8">
            @if($medicalCare->description)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Description</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $medicalCare->description }}</p>
                    </div>
                </div>
            @endif

            @if($medicalCare->notes)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Notes</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $medicalCare->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection