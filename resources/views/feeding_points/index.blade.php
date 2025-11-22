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

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-muted mb-1">Cartographie</p>
                <h2 class="h5 fw-semibold mb-0">Carte des points de nourrissage</h2>
            </div>
            <span class="badge bg-soft-primary text-primary">Leaflet</span>
        </div>
        <div id="feedingMap" class="map-container"></div>
    </div>
</div>
@endsection

@push('styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
>
@endpush

@push('scripts')
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const map = L.map('feedingMap');
        const points = @json($feedingPoints->map(fn($p) => [
            'name' => $p->name,
            'lat' => $p->latitude,
            'lng' => $p->longitude,
            'volunteers' => $p->volunteers->pluck('full_name'),
        ])->values());

        const fallback = [46.2276, 2.2137]; // centre France

        if (points.length) {
            const bounds = L.latLngBounds(points.map(p => [p.lat, p.lng]));
            map.fitBounds(bounds.pad(0.2));
        } else {
            map.setView(fallback, 5);
        }

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        points.forEach(point => {
            const marker = L.marker([point.lat, point.lng]).addTo(map);
            const volunteerList = point.volunteers.length
                ? `<ul class="mb-0 ps-3">${point.volunteers.map(v => `<li>${v}</li>`).join('')}</ul>`
                : '<p class="mb-0 text-muted">Aucun bénévole associé</p>';

            marker.bindPopup(`
                <div class="fw-semibold mb-1">${point.name}</div>
                ${volunteerList}
            `);
        });
    });
</script>
@endpush
