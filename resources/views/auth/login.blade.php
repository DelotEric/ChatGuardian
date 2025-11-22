@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="brand-circle mx-auto mb-3">🐾</div>
                    <h1 class="h4 fw-bold mb-1">Connexion</h1>
                    <p class="text-muted">Accédez à votre espace ChatGuardian</p>
                </div>
                <form>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="vous@example.org">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label mb-0">Mot de passe</label>
                            <a href="#" class="small">Mot de passe oublié ?</a>
                        </div>
                        <input type="password" id="password" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center text-muted small mt-3">Démo visuelle — authentification Laravel classique à brancher.</p>
    </div>
</div>
@endsection
