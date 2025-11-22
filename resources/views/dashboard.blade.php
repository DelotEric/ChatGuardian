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
                <p class="text-muted small mb-0">Cette page affiche désormais des données issues des tables chats, bénévoles,
 familles, dons et points de nourrissage.</p>
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
                    @endif
                    @if($role === 'admin')
                        <a class="list-group-item list-group-item-action d-flex align-items-center" href="/donations">
                            <span class="quick-icon me-3">💶</span> Suivre les dons
                        </a>
                    @endif
                </div>
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
        <div class="col-12 col-lg-5">
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
    @endif
</div>
@endsection
