@extends('layouts.app')

@section('content')
    <style>
        .stat-card {
            border-radius: 12px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .widget-card {
            border-radius: 12px;
            border: none;
        }

        .widget-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px 12px 0 0;
            padding: 1.25rem;
            border-bottom: 1px solid #e9ecef;
        }

        .event-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .news-item {
            transition: background 0.2s;
            border-radius: 8px;
            padding: 0.75rem;
            margin: -0.75rem;
        }

        .news-item:hover {
            background: #f8f9fa;
        }
    </style>

    <div class="mb-4">
        <h1 class="h3 fw-bold" style="color: #2d3748;">
            <i class="bi bi-house-door-fill" style="color: #3a7e8c;"></i>
            Tableau de bord
        </h1>
        <p class="text-muted">Bienvenue, {{ Auth::user()->name }}. Voici un aperçu de l'activité.</p>
    </div>

    <!-- Urgent Items Summary -->
    @if($urgentItemsCount > 0)
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 5px solid #dc3545;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="alert-heading mb-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>{{ $urgentItemsCount }} élément(s) nécessitant une attention immédiate</strong>
                    </h5>
                    <div class="d-flex gap-4 flex-wrap">
                        @if($overdueCare->count() > 0)
                            <div>
                                <i class="bi bi-clock-history text-danger"></i>
                                <strong>{{ $overdueCare->count() }}</strong> soin(s) en retard
                            </div>
                        @endif
                        @if($upcomingCareWeek->count() > 0)
                            <div>
                                <i class="bi bi-calendar-check text-warning"></i>
                                <strong>{{ $upcomingCareWeek->count() }}</strong> soin(s) cette semaine
                            </div>
                        @endif
                        @if($lowStockItems->count() > 0)
                            <div>
                                <i class="bi bi-box-seam text-warning"></i>
                                <strong>{{ $lowStockItems->count() }}</strong> article(s) en stock faible
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                        @if($overdueCare->count() > 0 || $upcomingCareWeek->count() > 0)
                            <a href="{{ route('medical-cares.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-clipboard2-pulse"></i> Voir soins
                            </a>
                        @endif
                        @if($lowStockItems->count() > 0)
                            <a href="{{ route('inventory-items.index') }}?low_stock=1" class="btn btn-light btn-sm">
                                <i class="bi bi-box-seam"></i> Voir stock
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cartes de statistiques -->
    <div class="row g-4 mb-4">
        <!-- Chats -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Chats</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $totalCats }}</h2>
                        <div class="mt-1 small">
                            <span class="badge bg-success">{{ $catsForAdoption }}</span> à l'adoption
                            <span class="mx-1">•</span>
                            <span class="badge bg-info">{{ $catsInFoster }}</span> en accueil
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bénévoles -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3"
                        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Bénévoles</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ $totalVolunteers }}</h2>
                        <div class="mt-1 small">
                            <span class="badge bg-success">{{ $activeVolunteers }}</span> actifs
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux d'occupation -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3"
                            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                            <i class="bi bi-house-heart-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Taux d'occupation</h6>
                            <h2 class="display-6 fw-bold mb-0">{{ $occupancyRate }}%</h2>
                        </div>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar {{ $occupancyRate >= 90 ? 'bg-danger' : ($occupancyRate >= 70 ? 'bg-warning' : 'bg-success') }}"
                            role="progressbar" style="width: {{ $occupancyRate }}%;" aria-valuenow="{{ $occupancyRate }}"
                            aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-check-circle"></i> {{ $availableSpots }} place(s) disponible(s)
                        <br>
                        <i class="bi bi-info-circle"></i> {{ $currentOccupancy }}/{{ $totalCapacity }} occupées
                    </div>
                </div>
            </div>
        </div>

        <!-- Dons ce mois -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3"
                        style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Dons ce mois</h6>
                        <h2 class="display-6 fw-bold mb-0">{{ number_format($donationsThisMonth, 0) }} €</h2>
                        <div class="mt-1 small text-success">
                            <i class="bi bi-graph-up"></i> Merci !
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes Soins médicaux -->
    @if($overdueCare->count() > 0 || $upcomingCareWeek->count() > 0)
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card widget-card shadow-sm">
                    <div class="widget-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                <strong>Alertes soins médicaux</strong>
                            </h5>
                            <a href="{{ route('medical-cares.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-clipboard2-pulse"></i> Voir tous
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($overdueCare->count() > 0)
                                <div class="col-lg-6">
                                    <div class="alert alert-danger border-0 shadow-sm">
                                        <h6 class="alert-heading"><i class="bi bi-exclamation-circle-fill"></i> Soins en retard
                                            ({{ $overdueCare->count() }})</h6>
                                        <ul class="mb-0 small">
                                            @foreach($overdueCare->take(3) as $care)
                                                <li>
                                                    <strong>{{ $care->cat->name }}</strong> - {{ $care->care_type }}
                                                    <span class="text-muted">({{ $care->care_date->format('d/m/Y') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if($upcomingCareWeek->count() > 0)
                                <div class="col-lg-6">
                                    <div class="alert alert-warning border-0 shadow-sm">
                                        <h6 class="alert-heading"><i class="bi bi-calendar-check"></i> Cette semaine
                                            ({{ $upcomingCareWeek->count() }})</h6>
                                        <ul class="mb-0 small">
                                            @foreach($upcomingCareWeek->take(3) as $care)
                                                <li>
                                                    <strong>{{ $care->cat->name }}</strong> - {{ $care->care_type }}
                                                    <span class="text-muted">({{ $care->care_date->format('d/m/Y') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card widget-card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">
                        <i class="bi bi-lightning-fill text-warning"></i> Actions rapides
                    </h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('cats.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Nouveau chat
                        </a>
                        <a href="{{ route('donations.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-gift"></i> Enregistrer un don
                        </a>
                        <a href="{{ route('medical-cares.create') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-clipboard2-pulse"></i> Planifier un soin
                        </a>
                        <a href="{{ route('volunteers.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-person-plus"></i> Ajouter bénévole
                        </a>
                        <a href="{{ route('foster-families.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-house-add"></i> Ajouter famille
                        </a>
                        <a href="{{ route('inventory-items.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-box-seam"></i> Gérer inventaire
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock faible -->
    @if($lowStockItems->count() > 0)
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">⚠️ Stock faible détecté</h6>
                        <p class="mb-0 small">
                            {{ $lowStockItems->count() }} article(s) en stock faible.
                            <a href="{{ route('inventory-items.index') }}?low_stock=1" class="alert-link">Voir l'inventaire
                                →</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Actualités & Événements -->
    <div class="row g-4 mb-4">
        <!-- Actualités -->
        <div class="col-lg-8">
            <div class="card widget-card shadow-sm">
                <div class="widget-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-newspaper" style="color: #667eea;"></i>
                            <strong>Actualités de l'association</strong>
                        </h5>
                        <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary">Toutes</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($recentNews as $news)
                        <div class="news-item pb-3 mb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <h6 class="mb-2"><i class="bi bi-chevron-right text-primary"></i> {{ $news->title }}</h6>
                            <p class="text-muted small mb-2">{{ $news->short_content }}</p>
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i> {{ $news->publish_date->format('d/m/Y') }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-person"></i> {{ $news->author->name }}
                            </small>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-newspaper fs-1 mb-2 d-block opacity-50"></i>
                            <p class="mb-0">Aucune actualité récente</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Événements à venir -->
        <div class="col-lg-4">
            <div class="card widget-card shadow-sm">
                <div class="widget-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-event" style="color: #f5576c;"></i>
                            <strong>Événements</strong>
                        </h5>
                        <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-primary">Agenda</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($upcomingEvents as $event)
                        <div class="p-3 mb-3 rounded shadow-sm"
                            style="background: {{ $event->type === 'adoption_day' ? 'linear-gradient(135deg, #667eea15 0%, #764ba215 100%)' : ($event->type === 'training' ? 'linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%)' : '#f8f9fa') }}; border-left: 4px solid {{ $event->type === 'adoption_day' ? '#667eea' : ($event->type === 'training' ? '#f5576c' : '#6c757d') }};">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-semibold">{{ $event->title }}</span>
                                <span
                                    class="event-badge {{ $event->type === 'adoption_day' ? 'bg-primary' : ($event->type === 'training' ? 'bg-danger' : 'bg-secondary') }} text-white">
                                    {{ $event->formatted_date }}
                                </span>
                            </div>
                            @if($event->location || $event->event_time)
                                <small class="text-muted d-block">
                                    @if($event->location)
                                        <i class="bi bi-geo-alt-fill"></i> {{ $event->location }}
                                    @endif
                                    @if($event->event_time)
                                        <span class="ms-2"><i class="bi bi-clock-fill"></i>
                                            {{ substr($event->event_time, 0, 5) }}</span>
                                    @endif
                                </small>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-1 mb-2 d-block opacity-50"></i>
                            <p class="mb-0 small">Aucun événement prévu</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers chats & Dons -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card widget-card shadow-sm">
                <div class="widget-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-heart-fill" style="color: #f5576c;"></i>
                            <strong>Derniers chats enregistrés</strong>
                        </h5>
                        <a href="{{ route('cats.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($latestCats as $cat)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-3 d-flex align-items-center justify-center"
                                                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                    {{ strtoupper(substr($cat->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $cat->name }}</div>
                                                    <small class="text-muted">{{ $cat->sex }} • {{ $cat->color }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-{{ $cat->status === 'A l\'adoption' ? 'success' : 'info' }}">
                                                {{ $cat->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted py-3">Aucun chat enregistré</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card widget-card shadow-sm">
                <div class="widget-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-gift-fill" style="color: #43e97b;"></i>
                            <strong>Derniers dons</strong>
                        </h5>
                        <a href="{{ route('donations.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($latestDonations as $donation)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $donation->donor->name }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3"></i>
                                                {{ \Carbon\Carbon::parse($donation->donated_at)->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-bold text-success">{{ number_format($donation->amount, 2) }} €</div>
                                            <small class="text-muted">{{ ucfirst($donation->payment_method) }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted py-3">Aucun don enregistré</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection