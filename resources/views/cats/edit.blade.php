@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('cats.index') }}" class="text-decoration-none text-muted">Chats</a>
                / Édition</p>
            <h1 class="h4 fw-bold">Modifier {{ $cat->name }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('cats.update', $cat) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <h5 class="mb-3">Photos</h5>
                    </div>

                    <div class="col-md-6">
                        <label for="photo" class="form-label">Photo de profil</label>
                        @if($cat->photo_path)
                            <div class="mb-2">
                                <img src="{{ Storage::url($cat->photo_path) }}" alt="Photo de profil" class="img-thumbnail"
                                    style="height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo"
                            accept="image/*">
                        <div class="form-text">Format accepté : jpg, png, jpeg. Max 2Mo.</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="gallery_photos" class="form-label">Ajouter à la galerie</label>
                        <input type="file" class="form-control @error('gallery_photos') is-invalid @enderror"
                            id="gallery_photos" name="gallery_photos[]" accept="image/*" multiple>
                        <div class="form-text">Vous pouvez sélectionner plusieurs photos.</div>
                        @error('gallery_photos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($cat->photos->count() > 0)
                        <div class="col-12">
                            <label class="form-label">Galerie actuelle</label>
                            <div class="row g-2">
                                @foreach($cat->photos as $photo)
                                    <div class="col-auto position-relative">
                                        <img src="{{ Storage::url($photo->path) }}" class="img-thumbnail" style="height: 100px;">
                                        <div class="form-check position-absolute top-0 end-0 m-1">
                                            <input class="form-check-input bg-danger border-danger" type="checkbox"
                                                name="delete_gallery_photos[]" value="{{ $photo->id }}"
                                                id="delete_photo_{{ $photo->id }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text text-danger">Cochez les photos à supprimer.</div>
                        </div>
                    @endif

                    <div class="col-12">
                        <hr class="my-2">
                        <h5 class="mb-3">Informations générales</h5>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $cat->name) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sexe</label>
                        <select name="sex" class="form-select" required>
                            <option value="male" {{ old('sex', $cat->sex) === 'male' ? 'selected' : '' }}>Mâle</option>
                            <option value="female" {{ old('sex', $cat->sex) === 'female' ? 'selected' : '' }}>Femelle</option>
                            <option value="unknown" {{ old('sex', $cat->sex) === 'unknown' ? 'selected' : '' }}>Inconnu
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="birthdate" class="form-control"
                            value="{{ old('birthdate', optional($cat->birthdate)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select" required>
                            <option value="free" {{ old('status', $cat->status) === 'free' ? 'selected' : '' }}>Libre</option>
                            <option value="A l'adoption" {{ old('status', $cat->status) === 'A l\'adoption' ? 'selected' : '' }}>A l'adoption</option>
                            <option value="adopted" {{ old('status', $cat->status) === 'adopted' ? 'selected' : '' }}>Adopté
                            </option>
                            <option value="deceased" {{ old('status', $cat->status) === 'deceased' ? 'selected' : '' }}>Décédé
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Adoptant</label>
                        <select name="adopter_id" class="form-select">
                            <option value="">Aucun</option>
                            @foreach(\App\Models\Adopter::orderBy('name')->get() as $adopter)
                                <option value="{{ $adopter->id }}" {{ old('adopter_id', $cat->adopter_id) == $adopter->id ? 'selected' : '' }}>
                                    {{ $adopter->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date d'adoption</label>
                        <input type="date" name="adopted_at" class="form-control"
                            value="{{ old('adopted_at', optional($cat->adopted_at)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-12">
                        <hr class="my-2">
                        <h5 class="mb-3">Santé</h5>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Stérilisé</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="sterilized" id="sterilized" {{ old('sterilized', $cat->sterilized) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sterilized">Oui</label>
                        </div>
                        <input type="date" name="sterilized_at" class="form-control mt-1" placeholder="Date"
                            value="{{ old('sterilized_at', optional($cat->sterilized_at)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Vacciné</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="vaccinated" id="vaccinated" {{ old('vaccinated', $cat->vaccinated) ? 'checked' : '' }}>
                            <label class="form-check-label" for="vaccinated">Oui</label>
                        </div>
                        <input type="date" name="vaccinated_at" class="form-control mt-1" placeholder="Date"
                            value="{{ old('vaccinated_at', optional($cat->vaccinated_at)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">FIV</label>
                        <select name="fiv_status" class="form-select">
                            <option value="unknown" {{ old('fiv_status', $cat->fiv_status) === 'unknown' ? 'selected' : '' }}>
                                Inconnu</option>
                            <option value="positive" {{ old('fiv_status', $cat->fiv_status) === 'positive' ? 'selected' : '' }}>
                                Positif</option>
                            <option value="negative" {{ old('fiv_status', $cat->fiv_status) === 'negative' ? 'selected' : '' }}>
                                Négatif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">FELV</label>
                        <select name="felv_status" class="form-select">
                            <option value="unknown" {{ old('felv_status', $cat->felv_status) === 'unknown' ? 'selected' : '' }}>
                                Inconnu</option>
                            <option value="positive" {{ old('felv_status', $cat->felv_status) === 'positive' ? 'selected' : '' }}>
                                Positif</option>
                            <option value="negative" {{ old('felv_status', $cat->felv_status) === 'negative' ? 'selected' : '' }}>
                                Négatif</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Observations, soins...">{{ old('notes', $cat->notes) }}</textarea>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('cats.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection