@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3"><i class="bi bi-calendar-event"></i> Agenda - Événements</h1>
            <a href="{{ route('events.create') }}" class="btn btn-primary">+ Nouvel événement</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Événement</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Lieu</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td><strong>{{ $event->title }}</strong></td>
                                    <td>{{ $event->event_date->format('d/m/Y') }}</td>
                                    <td>{{ $event->event_time ?? '-' }}</td>
                                    <td>{{ $event->location ?? '-' }}</td>
                                    <td><span class="badge bg-info">{{ $event->type_label }}</span></td>
                                    <td>
                                        @if($event->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('events.edit', $event) }}"
                                            class="btn btn-sm btn-outline-secondary">Modifier</a>
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Aucun événement</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $events->links() }}
        </div>
    </div>
@endsection