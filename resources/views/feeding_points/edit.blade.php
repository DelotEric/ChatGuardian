@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('feeding-points.index') }}"
                    class="text-decoration-none text-muted">Points de nourrissage</a> / Édition</p>
            <h1 class="h4 fw-bold">Modifier {{ $feedingPoint->name }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('feeding-points.update', $feedingPoint) }}">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $feedingPoint->name) }}"
                        required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="0.0000001" name="latitude" class="form-control"
                        value="{{ old('latitude', $feedingPoint->latitude) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="0.0000001" name="longitude" class="form-control"
                        value="{{ old('longitude', $feedingPoint->longitude) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"
                        placeholder="Repères, fréquence de nourrissage...">{{ old('description', $feedingPoint->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Bénévoles assignés</label>
                    <select name="volunteer_ids[]" class="form-select" multiple style="height: 150px;">
                        @foreach($volunteers as $volunteer)
                            <option value="{{ $volunteer->id }}" {{ $feedingPoint->volunteers->contains($volunteer->id) ? 'selected' : '' }}>
                                {{ $volunteer->full_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs bénévoles.</div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('feeding-points.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection