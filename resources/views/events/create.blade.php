@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-4"><i class="bi bi-calendar-event"></i> Nouvel événement</h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                            value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="event_date" class="form-label">Date *</label>
                            <input type="date" class="form-control @error('event_date') is-invalid @enderror"
                                id="event_date" name="event_date" value="{{ old('event_date') }}" required>
                            @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="event_time" class="form-label">Heure</label>
                            <input type="time" class="form-control @error('event_time') is-invalid @enderror"
                                id="event_time" name="event_time" value="{{ old('event_time') }}">
                            @error('event_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Lieu</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                                name="location" value="{{ old('location') }}">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type *</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="adoption_day" {{ old('type') == 'adoption_day' ? 'selected' : '' }}>Journée
                                    d'adoption</option>
                                <option value="training" {{ old('type') == 'training' ? 'selected' : '' }}>Formation</option>
                                <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>Réunion</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Événement actif
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('events.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection