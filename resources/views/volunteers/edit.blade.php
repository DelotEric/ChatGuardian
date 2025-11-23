@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('volunteers.index') }}"
                    class="text-decoration-none text-muted">Bénévoles</a> / Édition</p>
            <h1 class="h4 fw-bold">Modifier {{ $volunteer->full_name }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('volunteers.update', $volunteer) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input name="first_name" type="text" class="form-control"
                            value="{{ old('first_name', $volunteer->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input name="last_name" type="text" class="form-control"
                            value="{{ old('last_name', $volunteer->last_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', $volunteer->email) }}"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input name="phone" type="text" class="form-control" value="{{ old('phone', $volunteer->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ville / zone</label>
                        <input name="city" type="text" class="form-control" value="{{ old('city', $volunteer->city) }}"
                            placeholder="Quartier / ville">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Disponibilités</label>
                        <input name="availability" type="text" class="form-control"
                            value="{{ old('availability', $volunteer->availability) }}" placeholder="Soirs, week-ends...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Compétences</label>
                        <textarea name="skills" class="form-control" rows="2"
                            placeholder="Captures, soins, transport...">{{ old('skills', $volunteer->skills) }}</textarea>
                    </div>
                    <div class="col-12 form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" {{ old('is_active', $volunteer->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Actif</label>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('volunteers.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection