@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('cats.index') }}" class="text-decoration-none text-muted">Chats</a> /
                Nouveau</p>
            <h1 class="h3 fw-bold">Ajouter un chat</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('cats.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="free" {{ old('status') == 'free' ? 'selected' : '' }}>Libre</option>
                            <option value="A l'adoption" {{ old('status') == 'A l\'adoption' ? 'selected' : '' }}>A l'adoption
                            </option>
                            <option value="adopted" {{ old('status') == 'adopted' ? 'selected' : '' }}>Adopté</option>
                            <option value="deceased" {{ old('status') == 'deceased' ? 'selected' : '' }}>Décédé</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="sex" class="form-label">Sexe <span class="text-danger">*</span></label>
                        <select class="form-select @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                            <option value="unknown" {{ old('sex') == 'unknown' ? 'selected' : '' }}>Inconnu</option>
                            <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Mâle</option>
                            <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Femelle</option>
                        </select>
                        @error('sex')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="birthdate" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate"
                            name="birthdate" value="{{ old('birthdate') }}">
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="adopter_id" class="form-label">Adoptant</label>
                        <select class="form-select @error('adopter_id') is-invalid @enderror" id="adopter_id" name="adopter_id">
                            <option value="">Aucun</option>
                            @foreach(\App\Models\Adopter::orderBy('name')->get() as $adopter)
                                <option value="{{ $adopter->id }}" {{ old('adopter_id') == $adopter->id ? 'selected' : '' }}>
                                    {{ $adopter->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('adopter_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="adopted_at" class="form-label">Date d'adoption</label>
                        <input type="date" class="form-control @error('adopted_at') is-invalid @enderror" id="adopted_at"
                            name="adopted_at" value="{{ old('adopted_at') }}">
                        @error('adopted_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr class="my-2">
                        <h5 class="mb-3">Photos</h5>
                    </div>

                    <div class="col-md-6">
                        <label for="photo" class="form-label">Photo de profil</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo"
                            name="photo" accept="image/*">
                        <div class="form-text">Format accepté : jpg, png, jpeg. Max 2Mo.</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="gallery_photos" class="form-label">Galerie photos</label>
                        <input type="file" class="form-control @error('gallery_photos') is-invalid @enderror"
                            id="gallery_photos" name="gallery_photos[]" accept="image/*" multiple>
                        <div class="form-text">Vous pouvez sélectionner plusieurs photos.</div>
                        @error('gallery_photos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr class="my-2">
                        <h5 class="mb-3">Santé</h5>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="sterilized" name="sterilized" value="1"
                                {{ old('sterilized') ? 'checked' : '' }}>
                            <label class="form-check-label" for="sterilized">Stérilisé</label>
                        </div>
                        <div class="mb-3">
                            <label for="sterilized_at" class="form-label small text-muted">Date de stérilisation</label>
                            <input type="date" class="form-control form-control-sm" id="sterilized_at"
                                name="sterilized_at" value="{{ old('sterilized_at') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="vaccinated" name="vaccinated" value="1"
                                {{ old('vaccinated') ? 'checked' : '' }}>
                            <label class="form-check-label" for="vaccinated">Vacciné</label>
                        </div>
                        <div class="mb-3">
                            <label for="vaccinated_at" class="form-label small text-muted">Date de vaccination</label>
                            <input type="date" class="form-control form-control-sm" id="vaccinated_at"
                                name="vaccinated_at" value="{{ old('vaccinated_at') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="fiv_status" class="form-label">Statut FIV <span class="text-danger">*</span></label>
                        <select class="form-select @error('fiv_status') is-invalid @enderror" id="fiv_status"
                            name="fiv_status" required>
                            <option value="unknown" {{ old('fiv_status') == 'unknown' ? 'selected' : '' }}>Inconnu</option>
                            <option value="positive" {{ old('fiv_status') == 'positive' ? 'selected' : '' }}>Positif
                            </option>
                            <option value="negative" {{ old('fiv_status') == 'negative' ? 'selected' : '' }}>Négatif
                            </option>
                        </select>
                        @error('fiv_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="felv_status" class="form-label">Statut FELV <span class="text-danger">*</span></label>
                        <select class="form-select @error('felv_status') is-invalid @enderror" id="felv_status"
                            name="felv_status" required>
                            <option value="unknown" {{ old('felv_status') == 'unknown' ? 'selected' : '' }}>Inconnu
                            </option>
                            <option value="positive" {{ old('felv_status') == 'positive' ? 'selected' : '' }}>Positif
                            </option>
                            <option value="negative" {{ old('felv_status') == 'negative' ? 'selected' : '' }}>Négatif
                            </option>
                        </select>
                        @error('felv_status')
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

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('cats.index') }}" class="btn btn-light border me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
