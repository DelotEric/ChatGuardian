@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="brand-circle mx-auto mb-3">🔒</div>
                    <h1 class="h4 fw-bold mb-1">Nouveau mot de passe</h1>
                    <p class="text-muted">Saisissez et confirmez votre nouveau mot de passe.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger small">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" class="form-control" placeholder="vous@example.org" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Retour à la connexion</a>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center text-muted small mt-3">Votre session sera prête après connexion.</p>
    </div>
</div>
@endsection
