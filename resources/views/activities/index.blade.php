@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 mb-1">Journal d'activités</h1>
        <p class="text-muted mb-0">Traçabilité des actions clés réalisées par l'équipe.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('activities.export', request()->query()) }}" class="btn btn-outline-secondary"><i class="bi bi-download me-2"></i>Exporter CSV</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Total enregistré</p>
                <h4 class="mb-0">{{ $stats['total'] }} activités</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-1">7 derniers jours</p>
                <h4 class="mb-0">{{ $stats['week'] }} activités</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted mb-1">30 derniers jours</p>
                <h4 class="mb-0">{{ $stats['month'] }} activités</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Action</label>
                <select name="action" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sujet</label>
                <select name="subject_type" class="form-select">
                    <option value="">Tous</option>
                    @foreach($subjectTypes as $type)
                        <option value="{{ $type }}" @selected(request('subject_type') === $type)>{{ class_basename($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Utilisateur</label>
                <input type="number" name="user_id" class="form-control" placeholder="ID" value="{{ request('user_id') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Période</label>
                <select name="period" class="form-select">
                    <option value="">Aucune</option>
                    <option value="today" @selected(request('period') === 'today')>Aujourd'hui</option>
                    <option value="7d" @selected(request('period') === '7d')>7 jours</option>
                    <option value="30d" @selected(request('period') === '30d')>30 jours</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-2"></i>Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Sujet</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td class="text-nowrap">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-semibold mb-0">{{ optional($activity->user)->name ?? 'Système' }}</div>
                                <small class="text-muted">{{ optional($activity->user)->role }}</small>
                            </td>
                            <td><span class="badge bg-soft-primary text-primary">{{ $activity->action }}</span></td>
                            <td class="text-muted">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</td>
                            <td>{{ $activity->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucune activité trouvée pour ces filtres.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
