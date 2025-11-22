@extends('layouts.app')

@section('content')
@php $role = auth()->user()->role ?? null; @endphp
<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="text-muted mb-1">Bienvenue</p>
                        <h2 class="h4 fw-bold">Vue d'ensemble de l'association</h2>
                    </div>
                    <span class="badge bg-soft-primary text-primary">Données réelles</span>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Chats</p>
                            <p class="h4 mb-0">{{ $metrics['cats'] }}</p>
                            <small class="text-muted">Répartition ci-dessous</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Familles actives</p>
                            <p class="h4 mb-0">{{ $metrics['families'] }}</p>
                            <small class="text-muted">prêtes à accueillir</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Bénévoles</p>
                            <p class="h4 mb-0">{{ $metrics['volunteers'] }}</p>
                            <small class="text-muted">contactables</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Dons (mois)</p>
                            <p class="h4 mb-0">{{ number_format($metrics['donations_month'], 2, ',', ' ') }} €</p>
                            <small class="text-muted">reçus ce mois</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Adoptions (mois)</p>
                            <p class="h4 mb-0">{{ $metrics['adoptions_month'] }}</p>
                            <small class="text-muted">confirmées</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Soins véto (mois)</p>
                            <p class="h4 mb-0">{{ number_format($metrics['vet_month'], 2, ',', ' ') }} €</p>
                            <small class="text-muted">dépensés</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Visites véto (mois)</p>
                            <p class="h4 mb-0">{{ $metrics['vet_visits_month'] }}</p>
                            <small class="text-muted">enregistrées</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Points de nourrissage</p>
                            <p class="h4 mb-0">{{ $metrics['feeding_points'] }}</p>
                            <small class="text-muted">sites suivis</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-3">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Articles de stock</p>
                            <p class="h4 mb-0">{{ $metrics['stock_items'] }}</p>
                            <small class="text-muted">inventaire suivi</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="stat-card {{ $metrics['stock_low'] ? 'border-warning' : '' }}">
                            <p class="text-muted mb-1">Alertes stock</p>
                            <p class="h4 mb-0">{{ $metrics['stock_low'] }}</p>
                            <small class="text-muted">à réapprovisionner</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    @php
                        $labels = [
                            'free' => 'Chats libres',
                            'fostered' => 'En famille d\'accueil',
                            'adopted' => 'Adoptés',
                            'deceased' => 'Décédés',
                        ];
                    @endphp
                    @foreach($labels as $status => $label)
                        <div class="col-12 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-1">{{ $label }}</p>
                                        <p class="h5 mb-0">{{ $catTotals[$status] ?? 0 }}</p>
                                    </div>
                                    <span class="badge bg-soft-secondary text-secondary text-uppercase small">{{ $status }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row g-3 mb-2">
                    <div class="col-12 col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h3 class="h6 fw-semibold mb-0">Répartition des statuts</h3>
                                    <span class="badge bg-soft-primary text-primary">Temps réel</span>
                                </div>
                                <canvas id="catStatusChart" height="180"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h3 class="h6 fw-semibold mb-0">Dons des 6 derniers mois</h3>
                                    <span class="badge bg-soft-success text-success">€</span>
                                </div>
                                <canvas id="donationsChart" height="180"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mb-0">Cette page affiche désormais des données issues des tables chats, bénévoles, familles, dons et points de nourrissage avec des graphiques de suivi.</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h3 class="h6 fw-semibold mb-3">Actions rapides</h3>
                <div class="list-group list-group-flush">
                    @if($role === 'admin')
                        <a class="list-group-item list-group-item-action d-flex align-items-center" href="/volunteers">
                            <span class="quick-icon me-3">🤝</span> Gérer les bénévoles
                        </a>
                    @endif
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="/cats">
                        <span class="quick-icon me-3">🐱</span> Voir les chats
                    </a>
                    @if(in_array($role, ['admin', 'benevole']))
                        <a class="list-group-item list-group-item-action d-flex align-items-center" href="/foster-families">
                            <span class="quick-icon me-3">📄</span> Générer un contrat d'accueil
                        </a>
                        <a class="list-group-item list-group-item-action d-flex align-items-center" href="/feeding-points">
                            <span class="quick-icon me-3">🧭</span> Configurer un point de nourrissage
                        </a>
                        <a class="list-group-item list-group-item-action d-flex align-items-center" href="/stocks">
                            <span class="quick-icon me-3">📦</span> Vérifier les stocks
                        </a>
                    @endif
                    @if($role === 'admin')
                        <a class="list-group-item list-group-item-action d-flex align-items-center" href="/donations">
                            <span class="quick-icon me-3">💶</span> Suivre les dons
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 fw-semibold mb-0">Rappels à venir</h3>
                    <span class="badge bg-soft-warning text-warning">{{ $upcomingReminders->count() }}</span>
                </div>
                <p class="text-muted small mb-3">Vaccins, suivis santé et contrôles à planifier</p>
                <div class="list-group list-group-flush">
                    @forelse($upcomingReminders as $reminder)
                        <div class="list-group-item px-0 d-flex align-items-start justify-content-between">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $reminder->title }}</p>
                                <p class="text-muted small mb-0">{{ $reminder->cat?->name }} · {{ $reminder->due_date?->format('d/m') }}</p>
                            </div>
                            <span class="badge bg-soft-secondary text-secondary text-uppercase">{{ $reminder->type }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun rappel en attente.</p>
                    @endforelse
                </div>
                <div class="text-end mt-2">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('reminders.index') }}">Voir tous les rappels</a>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 fw-semibold mb-0">Coordonnées association</h3>
                    @if($role === 'admin')
                        <a href="{{ route('settings.organization') }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                    @endif
                </div>
                <p class="mb-1 fw-semibold">{{ $organization->name }}</p>
                <p class="mb-1 text-muted">{{ $organization->legal_name }}</p>
                <p class="mb-1">{{ $organization->address }}<br>{{ $organization->postal_code }} {{ $organization->city }}</p>
                <p class="mb-0 text-muted small">{{ $organization->email }} · {{ $organization->phone }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h3 class="h6 fw-semibold mb-0">Derniers chats ajoutés</h3>
                    <a href="/cats" class="btn btn-sm btn-outline-primary">Voir tous les chats</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th>Notes</th>
                                <th class="text-end">Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCats as $cat)
                                <tr>
                                    <td class="fw-semibold">{{ $cat->name }}</td>
                                    <td><span class="badge bg-soft-primary text-primary text-uppercase">{{ $cat->status }}</span></td>
                                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($cat->notes, 50) }}</td>
                                    <td class="text-end text-muted">{{ optional($cat->created_at)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Aucun chat pour le moment.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if($role === 'admin')
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h6 fw-semibold mb-0">Dernières adoptions</h3>
                        <a href="/cats" class="btn btn-sm btn-outline-secondary">Voir les chats</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Chat</th>
                                    <th>Adoptant</th>
                                    <th class="text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAdoptions as $adoption)
                                    <tr>
                                        <td class="fw-semibold">{{ optional($adoption->cat)->name ?? 'Chat inconnu' }}</td>
                                        <td class="text-muted">{{ $adoption->adopter_name }}</td>
                                        <td class="text-end text-muted">{{ optional($adoption->adopted_at)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Aucune adoption récente.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h6 fw-semibold mb-0">Dons récents</h3>
                        <a href="/donations" class="btn btn-sm btn-outline-primary">Suivre les dons</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Donateur</th>
                                    <th>Montant</th>
                                    <th class="text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDonations as $donation)
                                    <tr>
                                        <td class="fw-semibold">{{ optional($donation->donor)->name ?? 'Donateur inconnu' }}</td>
                                        <td>{{ number_format($donation->amount, 2, ',', ' ') }} €</td>
                                        <td class="text-end text-muted">{{ optional($donation->donated_at)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Aucun don enregistré.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h6 fw-semibold mb-0">Dernières visites véto</h3>
                        <a href="/cats" class="btn btn-sm btn-outline-secondary">Voir les fiches</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Chat</th>
                                    <th>Motif</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentVetRecords as $record)
                                    <tr>
                                        <td class="fw-semibold">{{ optional($record->cat)->name ?? 'Chat inconnu' }}</td>
                                        <td class="text-muted">{{ \Illuminate\Support\Str::limit($record->reason, 40) }}</td>
                                        <td class="text-end">{{ number_format($record->amount, 2, ',', ' ') }} €</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Aucune visite enregistrée.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <p class="text-muted mb-1">Traçabilité</p>
                <h3 class="h6 fw-semibold mb-0">Journal des activités</h3>
            </div>
            <span class="badge bg-soft-primary text-primary">Suivi</span>
        </div>
        <div class="list-group list-group-flush">
            @forelse($activities as $activity)
                <div class="list-group-item px-0 d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center"
                         style="width: 42px; height: 42px;">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-1 fw-semibold text-capitalize">{{ str_replace('.', ' · ', $activity->action) }}</p>
                            <small class="text-muted">{{ optional($activity->created_at)->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 text-muted">{{ $activity->description ?? 'Mise à jour enregistrée.' }}</p>
                        <small class="text-muted">Par {{ optional($activity->user)->name ?? 'Système' }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">Aucune activité récente.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const statusCtx = document.getElementById('catStatusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Chats libres', 'En famille d\'accueil', 'Adoptés', 'Décédés'],
                    datasets: [{
                        data: [
                            {{ $catTotals['free'] ?? 0 }},
                            {{ $catTotals['fostered'] ?? 0 }},
                            {{ $catTotals['adopted'] ?? 0 }},
                            {{ $catTotals['deceased'] ?? 0 }},
                        ],
                        backgroundColor: ['#6C5CE7', '#4ECDC4', '#FEB85B', '#E66767'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, usePointStyle: true },
                        },
                    },
                    cutout: '60%',
                },
            });
        }

        const donationsCtx = document.getElementById('donationsChart');
        if (donationsCtx) {
            new Chart(donationsCtx, {
                type: 'bar',
                data: {
                    labels: @json($donationChart->pluck('label')),
                    datasets: [{
                        label: 'Dons (€)',
                        data: @json($donationChart->pluck('value')),
                        backgroundColor: '#6C5CE7',
                        borderRadius: 6,
                        borderSkipped: false,
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => `${value} €`,
                            },
                            grid: { color: '#f1f3f5' },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });
        }
    </script>
@endpush
