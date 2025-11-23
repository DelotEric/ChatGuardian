@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('events.index') }}"
                    class="text-decoration-none text-muted">Événements</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $event->title }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('events.destroy', $event) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary me-2">{{ $event->type_label }}</span>
                        <span class="text-muted">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ $event->event_date->format('d/m/Y') }}
                            @if($event->event_time)
                                à {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                            @endif
                        </span>
                    </div>

                    @if($event->description)
                        <div class="mb-4">
                            <h5 class="fw-bold">Description</h5>
                            <p class="text-muted" style="white-space: pre-line;">{{ $event->description }}</p>
                        </div>
                    @endif

                    @if($event->location)
                        <div>
                            <h5 class="fw-bold">Lieu</h5>
                            <p class="mb-0"><i class="bi bi-geo-alt text-danger me-2"></i>{{ $event->location }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Informations</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Statut</span>
                            <span class="badge {{ $event->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $event->is_active ? 'Actif' : 'Terminé/Inactif' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Créé par</span>
                            <span class="fw-semibold">{{ $event->creator->name ?? 'Inconnu' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Créé le</span>
                            <span>{{ $event->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection