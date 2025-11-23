@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('foster-families.index') }}"
                    class="text-decoration-none text-muted">Familles d'accueil</a> / Édition</p>
            <h1 class="h4 fw-bold">Modifier {{ $fosterFamily->name }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('foster-families.update', $fosterFamily) }}">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Nom de la famille</label>
                    <input name="name" type="text" class="form-control" value="{{ old('name', $fosterFamily->name) }}"
                        required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email', $fosterFamily->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input name="phone" type="text" class="form-control" value="{{ old('phone', $fosterFamily->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Capacité</label>
                    <input name="capacity" type="number" class="form-control" min="1"
                        value="{{ old('capacity', $fosterFamily->capacity) }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Adresse</label>
                    <input name="address" type="text" class="form-control"
                        value="{{ old('address', $fosterFamily->address) }}" placeholder="Numéro et nom de rue">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Code postal</label>
                    <input name="postal_code" type="text" class="form-control"
                        value="{{ old('postal_code', $fosterFamily->postal_code) }}" placeholder="75000">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ville</label>
                    <input name="city" type="text" class="form-control" value="{{ old('city', $fosterFamily->city) }}"
                        placeholder="Paris">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Préférences</label>
                    <textarea name="preferences" class="form-control" rows="2"
                        placeholder="Chaton, adulte, sociable...">{{ old('preferences', $fosterFamily->preferences) }}</textarea>
                </div>
                <div class="col-12 form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="family_active" {{ old('is_active', $fosterFamily->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="family_active">Active</label>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('foster-families.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection