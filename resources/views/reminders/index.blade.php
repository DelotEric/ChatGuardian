@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-uppercase text-muted small mb-1">Suivi</p>
        <h1 class="h3 mb-0">Rappels chats</h1>
        <p class="text-muted mb-0">Points de contrôle à venir (vaccins, visites, suivis d'adoption...).</p>
    </div>
    <div class="text-end">
        <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">En retard</p>
                        <h4 class="mb-0">{{ $stats['overdue'] }}</h4>
                    </div>
                    <span class="badge bg-danger-subtle text-danger">Urgent</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">À venir (7j)</p>
                        <h4 class="mb-0">{{ $stats['week'] }}</h4>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">Planifier</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">À venir (30j)</p>
                        <h4 class="mb-0">{{ $stats['month'] }}</h4>
                    </div>
                    <span class="badge bg-info-subtle text-info">Anticiper</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Clôturés (mois)</p>
                        <h4 class="mb-0">{{ $stats['done_month'] }}</h4>
                    </div>
                    <span class="badge bg-success-subtle text-success">Effectué</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
            <div>
                <h5 class="mb-1">Rappels</h5>
                <p class="text-muted small mb-0">Filtrer par statut, échéance ou type d'action.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <form method="POST" action="{{ route('reminders.send_digest') }}" class="d-flex align-items-center gap-2">
                    @csrf
                    <select name="range" class="form-select form-select-sm">
                        <option value="week">7 prochains jours</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="overdue">En retard</option>
                    </select>
                    <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-envelope"></i> Envoyer par email</button>
                </form>
                <a href="{{ route('cats.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-plus"></i> Ajouter depuis une fiche chat</a>
            </div>
        </div>

        <form method="GET" class="row g-3 align-items-end mb-3">
            <div class="col-md-2">
                <label class="form-label small text-muted">Statut</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="pending" @selected($status === 'pending')>À faire</option>
                    <option value="done" @selected($status === 'done')>Clôturé</option>
                    <option value="all" @selected($status === 'all')>Tous</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Échéance</label>
                <select name="range" class="form-select form-select-sm">
                    <option value="upcoming" @selected($range === 'upcoming')>À venir</option>
                    <option value="week" @selected($range === 'week')>7 prochains jours</option>
                    <option value="overdue" @selected($range === 'overdue')>En retard</option>
                    <option value="all" @selected($range === 'all')>Sans filtre</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="" @selected(!$type)>Tous</option>
                    <option value="vet" @selected($type === 'vet')>Visite véto</option>
                    <option value="vaccine" @selected($type === 'vaccine')>Vaccin</option>
                    <option value="adoption_followup" @selected($type === 'adoption_followup')>Suivi adoption</option>
                    <option value="health" @selected($type === 'health')>Santé</option>
                    <option value="admin" @selected($type === 'admin')>Administratif</option>
                    <option value="other" @selected($type === 'other')>Autre</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Recherche</label>
                <input type="search" name="q" value="{{ $queryText }}" class="form-control form-control-sm" placeholder="Titre ou nom du chat">
            </div>
            <div class="col-md-1 text-end">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Échéance</th>
                        <th>Chat</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Notes</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reminders as $reminder)
                        <tr class="@if($reminder->status === 'pending' && $reminder->due_date->isPast()) table-danger-subtle @endif">
                            <td class="fw-semibold">{{ $reminder->due_date->translatedFormat('d M Y') }}</td>
                            <td>
                                <a href="{{ route('cats.show', $reminder->cat_id) }}" class="text-decoration-none fw-semibold">{{ $reminder->cat->name }}</a>
                                <div class="text-muted small">Ajouté par {{ optional($reminder->user)->name ?? '—' }}</div>
                            </td>
                            <td class="fw-semibold">{{ $reminder->title }}</td>
                            <td>
                                @php
                                    $types = [
                                        'vet' => 'Visite véto',
                                        'vaccine' => 'Vaccin',
                                        'adoption_followup' => 'Suivi adoption',
                                        'health' => 'Santé',
                                        'admin' => 'Administratif',
                                        'other' => 'Autre',
                                    ];
                                @endphp
                                <span class="badge bg-secondary-subtle text-secondary">{{ $types[$reminder->type] ?? $reminder->type }}</span>
                            </td>
                            <td class="text-muted small">{{ Str::limit($reminder->notes, 60) ?: '—' }}</td>
                            <td>
                                @if($reminder->status === 'done')
                                    <span class="badge bg-success-subtle text-success">Clôturé</span>
                                @elseif($reminder->due_date->isPast())
                                    <span class="badge bg-danger-subtle text-danger">En retard</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">À faire</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($reminder->status === 'pending')
                                        <form action="{{ route('cats.reminders.complete', [$reminder->cat_id, $reminder->id]) }}" method="POST" onsubmit="return confirm('Marquer ce rappel comme fait ?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success"><i class="bi bi-check2"></i></button>
                                        </form>
                                    @endif
                                    <a href="{{ route('cats.show', $reminder->cat_id) }}" class="btn btn-outline-secondary"><i class="bi bi-box-arrow-up-right"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucun rappel trouvé avec ces filtres.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted small mb-0">{{ $reminders->total() }} rappel(s)</p>
            {{ $reminders->links() }}
        </div>
    </div>
</div>
@endsection
