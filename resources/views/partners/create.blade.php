@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('partners.index') }}" class="text-decoration-none text-muted">Partenaires</a> /
                Nouveau</p>
            <h1 class="h3 fw-bold">Nouveau partenaire</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('partners.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="veterinarian" {{ old('type') == 'veterinarian' ? 'selected' : '' }}>Vétérinaire</option>
                            <option value="pet_store" {{ old('type') == 'pet_store' ? 'selected' : '' }}>Animalerie</option>
                            <option value="shelter" {{ old('type') == 'shelter' ? 'selected' : '' }}>Refuge</option>
                            <option value="supplier" {{ old('type') == 'supplier' ? 'selected' : '' }}>Fournisseur</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_person" class="form-label">Personne de contact</label>
                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person"
                            name="contact_person" value="{{ old('contact_person') }}">
                        @error('contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                            name="city" value="{{ old('city') }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                            name="address" value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="website" class="form-label">Site web</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website"
                            name="website" value="{{ old('website') }}" placeholder="https://...">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="discount_rate" class="form-label">Taux de remise (%)</label>
                        <input type="number" class="form-control @error('discount_rate') is-invalid @enderror" id="discount_rate"
                            name="discount_rate" value="{{ old('discount_rate') }}" min="0" max="100" step="0.01">
                        @error('discount_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="services" class="form-label">Services offerts</label>
                        <textarea class="form-control @error('services') is-invalid @enderror" id="services" name="services"
                            rows="3">{{ old('services') }}</textarea>
                        @error('services')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                            rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Partenaire actif
                            </label>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('partners.index') }}" class="btn btn-light border me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
