<section>
    <div class="mb-4">
        <p class="text-muted small">
            {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.') }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Mot de passe actuel') }}</label>
            <input type="password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                id="update_password_current_password" name="current_password" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                id="update_password_password" name="password" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation"
                class="form-label">{{ __('Confirmer le mot de passe') }}</label>
            <input type="password"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>

            @if (session('status') === 'password-updated')
                <span class="text-success small fade-message" x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 2000)">
                    <i class="bi bi-check-circle-fill"></i> {{ __('Enregistré.') }}
                </span>
            @endif
        </div>
    </form>
</section>