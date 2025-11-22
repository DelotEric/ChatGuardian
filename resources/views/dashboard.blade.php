@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="text-muted mb-1">Bienvenue</p>
                        <h2 class="h4 fw-bold">Vue d'ensemble de l'association</h2>
                    </div>
                    <span class="badge bg-soft-primary text-primary">Prototype</span>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Chats</p>
                            <p class="h4 mb-0">42</p>
                            <small class="text-success">+3 cette semaine</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Familles actives</p>
                            <p class="h4 mb-0">18</p>
                            <small class="text-muted">4 en attente</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <p class="text-muted mb-1">Dons (mois)</p>
                            <p class="h4 mb-0">2 450 €</p>
                            <small class="text-success">+12% vs N-1</small>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mb-0">Cette page servira de base pour le futur dashboard Laravel (widgets dynamiques, filtres, liens rapides).</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h3 class="h6 fw-semibold mb-3">Actions rapides</h3>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="/volunteers">
                        <span class="quick-icon me-3">🤝</span> Gérer les bénévoles
                    </a>
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="#">
                        <span class="quick-icon me-3">🐱</span> Ajouter un chat
                    </a>
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="#">
                        <span class="quick-icon me-3">📄</span> Générer un contrat d'accueil
                    </a>
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="#">
                        <span class="quick-icon me-3">🧭</span> Configurer un point de nourrissage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
