<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h2 class="mb-4 text-center">Connexion</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" 
                   class="form-control @error('password') is-invalid @enderror"
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" 
                   type="checkbox" 
                   class="form-check-input" 
                   name="remember">
            <label class="form-check-label" for="remember_me">
                Se souvenir de moi
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="text-decoration-none" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                Se connecter
            </button>
        </div>

        <div class="mt-3 text-center">
            <p class="mb-0">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-decoration-none">S'inscrire</a>
            </p>
        </div>
    </form>
</x-guest-layout>
