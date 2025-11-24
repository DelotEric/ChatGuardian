@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1">
                <a href="{{ route('cats.index') }}" class="text-decoration-none text-muted">Chats</a> / 
                <a href="{{ route('cats.show', $cat) }}" class="text-decoration-none text-muted">{{ $cat->name }}</a> / 
                Historique médical
            </p>
            <h1 class="h3 fw-bold">📋 Historique médical - {{ $cat->name }}</h1>
        </div>
        <div>
            <a href="{{ route('cats.health-record.download', $cat) }}" class="btn btn-primary">
                <i class="bi bi-download"></i> Télécharger carnet de santé PDF
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-clipboard2-pulse fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted small mb-1">Total soins</h6>
                            <h3 class="mb-0">{{ $stats['total_cares'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="bi bi-shield-check fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted small mb-1">Dernière vaccination</h6>
                            <h6 class="mb-0">
                                @if($stats['last_vaccination'])
                                    {{ $stats['last_vaccination']->care_date->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-calendar-event fs-4 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted small mb-1">Prochain soin</h6>
                            <h6 class="mb-0">
                                @if($stats['next_care'])
                                    {{ $stats['next_care']->care_date->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Aucun</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="bi bi-speedometer2 fs-4 text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted small mb-1">Poids actuel</h6>
                            <h3 class="mb-0">
                                @if($stats['latest_weight'])
                                    {{ $stats['latest_weight'] }} kg
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Courbe de poids -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Évolution du poids</h5>
                </div>
                <div class="card-body">
                    @if($cat->weightRecords->count() > 0)
                        <canvas id="weightChart" height="200"></canvas>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const ctx = document.getElementById('weightChart');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: {!! json_encode($cat->weightHistory()->pluck('measured_at')->map(fn($d) => $d->format('d/m/Y'))) !!},
                                    datasets: [{
                                        label: 'Poids (kg)',
                                        data: {!! json_encode($cat->weightHistory()->pluck('weight')) !!},
                                        borderColor: 'rgb(75, 192, 192)',
                                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                                        tension: 0.1,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: false
                                        }
                                    }
                                }
                            });
                        </script>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-graph-up fs-1 opacity-50"></i>
                            <p class="mt-2">Aucune donnée de poids enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historique des pesées -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Historique des pesées</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addWeightModal">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </button>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($cat->weightRecords as $record)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <strong>{{ $record->weight }} kg</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> {{ $record->measured_at->format('d/m/Y') }}
                                    @if($record->measured_by)
                                        • Par {{ $record->measured_by }}
                                    @endif
                                </small>
                                @if($record->notes)
                                    <br>
                                    <small class="text-muted">{{ $record->notes }}</small>
                                @endif
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editWeightModal{{ $record->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cats.weight-records.destroy', [$cat, $record]) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer cette pesée ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Edit Weight -->
                        <div class="modal fade" id="editWeightModal{{ $record->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('cats.weight-records.update', [$cat, $record]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier la pesée</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Poids (kg) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="weight" class="form-control" 
                                                       value="{{ $record->weight }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                                <input type="date" name="measured_at" class="form-control" 
                                                       value="{{ $record->measured_at->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Mesuré par</label>
                                                <input type="text" name="measured_by" class="form-control" 
                                                       value="{{ $record->measured_by }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea name="notes" class="form-control" rows="2">{{ $record->notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <p>Aucune pesée enregistrée</p>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addWeightModal">
                                <i class="bi bi-plus-circle"></i> Ajouter la première pesée
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Weight -->
    <div class="modal fade" id="addWeightModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('cats.weight-records.store', $cat) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une pesée</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Poids (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="weight" class="form-control" 
                                   placeholder="Ex: 3.5" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="measured_at" class="form-control" 
                                   value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mesuré par</label>
                            <input type="text" name="measured_by" class="form-control" 
                                   placeholder="Nom de la personne" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" 
                                      placeholder="Observations (optionnel)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Timeline médicale -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Timeline médicale</h5>
                </div>
                <div class="card-body">
                    @forelse($cat->medicalCares as $care)
                        <div class="d-flex mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded-circle p-2 {{ $care->status === 'completed' ? 'bg-success' : ($care->status === 'scheduled' ? 'bg-warning' : 'bg-secondary') }} bg-opacity-10">
                                    @switch($care->type)
                                        @case('vaccination')
                                            <i class="bi bi-shield-check fs-4 {{ $care->status === 'completed' ? 'text-success' : 'text-warning' }}"></i>
                                            @break
                                        @case('sterilization')
                                            <i class="bi bi-scissors fs-4 {{ $care->status === 'completed' ? 'text-success' : 'text-warning' }}"></i>
                                            @break
                                        @case('deworming')
                                            <i class="bi bi-capsule fs-4 {{ $care->status === 'completed' ? 'text-success' : 'text-warning' }}"></i>
                                            @break
                                        @default
                                            <i class="bi bi-hospital fs-4 {{ $care->status === 'completed' ? 'text-success' : 'text-warning' }}"></i>
                                    @endswitch
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $care->title }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3"></i> {{ $care->care_date->format('d/m/Y') }}
                                            @if($care->partner)
                                                • <i class="bi bi-building"></i> {{ $care->partner->name }}
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge {{ $care->status === 'completed' ? 'bg-success' : ($care->status === 'scheduled' ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ ucfirst($care->status) }}
                                    </span>
                                </div>
                                
                                @if($care->description)
                                    <p class="mt-2 mb-1">{{ $care->description }}</p>
                                @endif

                                @if($care->prescription)
                                    <div class="alert alert-info mt-2 mb-2">
                                        <strong><i class="bi bi-prescription2"></i> Prescription:</strong> {{ $care->prescription }}
                                        @if($care->dosage)
                                            <br><strong>Dosage:</strong> {{ $care->dosage }}
                                        @endif
                                        @if($care->duration)
                                            <br><strong>Durée:</strong> {{ $care->duration }}
                                        @endif
                                    </div>
                                @endif

                                @if($care->weight_at_visit)
                                    <small class="text-muted">
                                        <i class="bi bi-speedometer2"></i> Poids: {{ $care->weight_at_visit }} kg
                                    </small>
                                @endif

                                @if($care->cost)
                                    <small class="text-muted ms-3">
                                        <i class="bi bi-currency-euro"></i> {{ number_format($care->cost, 2) }} €
                                    </small>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-clipboard2-pulse fs-1 opacity-50"></i>
                            <p class="mt-2">Aucun soin médical enregistré</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
