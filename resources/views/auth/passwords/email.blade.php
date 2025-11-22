@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="brand-circle mx-auto mb-3">🔐</div>
                    <h1 class="h4 fw-bold mb-1">Réinitialiser le mot de passe</h1>
                    <p class="text-muted">Recevez un lien de réinitialisation par email.</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success small">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger small">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="vous@example.org" required autofocus>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Envoyer le lien</button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Retour à la connexion</a>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center text-muted small mt-3">Vérifiez vos spams si vous ne recevez rien.</p>
    </div>
</div>
@endsection
