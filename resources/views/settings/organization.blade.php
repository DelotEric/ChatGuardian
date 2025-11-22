@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <div class="flex-grow-1">
            <h1 class="h3 mb-0">Profil de l'association</h1>
            <p class="text-muted mb-0">Coordonnées et mentions légales utilisées dans les PDF (reçus, contrats) et les emails.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary rounded-pill">Admin</span>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.organization.update') }}" class="row g-3">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-6">
                            <label class="form-label">Nom usuel *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $organization->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dénomination légale</label>
                            <input type="text" name="legal_name" class="form-control" value="{{ old('legal_name', $organization->legal_name) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SIRET</label>
                            <input type="text" name="siret" class="form-control" value="{{ old('siret', $organization->siret) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email de contact</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $organization->email) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $organization->phone) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $organization->address) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Code postal</label>
                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $organization->postal_code) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $organization->city) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pays</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', $organization->country) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Site web</label>
                            <input type="text" name="website" class="form-control" value="{{ old('website', $organization->website) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">IBAN</label>
                            <input type="text" name="iban" class="form-control" value="{{ old('iban', $organization->iban) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">BIC</label>
                            <input type="text" name="bic" class="form-control" value="{{ old('bic', $organization->bic) }}">
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button class="btn btn-primary" type="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h2 class="h6 text-muted">Aperçu des coordonnées</h2>
                    <p class="mb-1 fw-semibold">{{ $organization->name }}</p>
                    <p class="mb-1 text-muted">{{ $organization->legal_name }}</p>
                    <p class="mb-1">{{ $organization->address }}<br>{{ $organization->postal_code }} {{ $organization->city }}<br>{{ $organization->country }}</p>
                    <p class="mb-0 text-muted">{{ $organization->email }} · {{ $organization->phone }}</p>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h6 text-muted">Utilisation</h2>
                    <ul class="mb-0 text-muted small">
                        <li>Référencé sur les reçus fiscaux PDF</li>
                        <li>Injecté dans le contrat de famille d'accueil</li>
                        <li>Signature et footer des emails automatiques</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
