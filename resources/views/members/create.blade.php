@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('members.index') }}"
                    class="text-decoration-none text-muted">Adhérents</a> / Nouveau</p>
            <h1 class="h3 fw-bold">Nouvel adhérent</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('members.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                            name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                            name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                            value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                            name="address" value="{{ old('address') }}">
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="postal_code" class="form-label">Code postal</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code"
                            name="postal_code" value="{{ old('postal_code') }}">
                        @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city"
                            value="{{ old('city') }}">
                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="birth_date" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date"
                            name="birth_date" value="{{ old('birth_date') }}">
                        @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="join_date" class="form-label">Date d'adhésion <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('join_date') is-invalid @enderror" id="join_date"
                            name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required>
                        @error('join_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Adhérent actif</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                            rows="3">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('members.index') }}" class="btn btn-light border me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection