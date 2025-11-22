@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-muted mb-1">Territoire</p>
        <h1 class="h4 fw-bold">Points de nourrissage</h1>
    </div>
    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#feedingForm">Ajouter</button>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div id="feedingForm" class="card shadow-sm border-0 collapse mb-4">
    <div class="card-body">
        <form class="row g-3" method="POST" action="{{ route('feeding-points.store') }}">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Nom</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Latitude</label>
                <input type="number" step="0.0000001" name="latitude" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Longitude</label>
                <input type="number" step="0.0000001" name="longitude" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Repères, fréquence de nourrissage..."></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Bénévoles assignés</label>
                <select name="volunteer_ids[]" class="form-select" multiple>
                    @foreach($volunteers as $volunteer)
                        <option value="{{ $volunteer->id }}">{{ $volunteer->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Ajouter le point</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($feedingPoints as $point)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">{{ $point->name }}</h5>
                            <p class="text-muted mb-2">{{ $point->latitude }}, {{ $point->longitude }}</p>
                        </div>
                        <span class="badge bg-soft-primary text-primary">{{ $point->volunteers->count() }} bénévole(s)</span>
                    </div>
                    <p class="mb-2">{{ $point->description ?? 'Pas de description' }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($point->volunteers as $volunteer)
                            <span class="badge bg-soft-secondary text-muted">{{ $volunteer->full_name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Aucun point de nourrissage pour l'instant.</p>
    @endforelse
</div>
@endsection
