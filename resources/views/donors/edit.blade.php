@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('donors.index') }}"
                    class="text-decoration-none text-muted">Donateurs</a> / Édition</p>
            <h1 class="h4 fw-bold">Modifier {{ $donor->name }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('donors.update', $donor) }}">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Nom complet</label>
                    <input name="name" type="text" class="form-control" value="{{ old('name', $donor->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email', $donor->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input name="phone" type="text" class="form-control" value="{{ old('phone', $donor->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Code postal</label>
                    <input name="postal_code" type="text" class="form-control"
                        value="{{ old('postal_code', $donor->postal_code) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ville</label>
                    <input name="city" type="text" class="form-control" value="{{ old('city', $donor->city) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Adresse</label>
                    <input name="address" type="text" class="form-control" value="{{ old('address', $donor->address) }}"
                        placeholder="Rue, numéro...">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                        placeholder="Informations complémentaires...">{{ old('notes', $donor->notes) }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('donors.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection