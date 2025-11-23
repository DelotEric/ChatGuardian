@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('feeding-points.index') }}"
                    class="text-decoration-none text-muted">Points de nourrissage</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $feedingPoint->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('feeding-points.edit', $feedingPoint) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('feeding-points.destroy', $feedingPoint) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce point ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 100px; height: 100px;">
                        <i class="bi bi-geo-alt fs-1 text-secondary"></i>
                    </div>
                    <h3 class="h5">{{ $feedingPoint->name }}</h3>
                    <p class="text-muted small mb-0">
                        {{ $feedingPoint->latitude }}, {{ $feedingPoint->longitude }}
                    </p>
                    <p class="text-muted small mb-0">
                        {{ $feedingPoint->latitude }}, {{ $feedingPoint->longitude }}
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Bénévoles assignés</div>
                <ul class="list-group list-group-flush">
                    @forelse($feedingPoint->volunteers as $volunteer)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('volunteers.show', $volunteer) }}"
                                class="text-decoration-none text-dark">{{ $volunteer->full_name }}</a>
                            <a href="tel:{{ $volunteer->phone }}" class="btn btn-sm btn-light"><i
                                    class="bi bi-telephone"></i></a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center">Aucun bénévole assigné.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Carte</div>
                <div class="card-body p-0">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Description & Notes</div>
                <div class="card-body">
                    <p class="mb-0">{{ $feedingPoint->description ?: 'Aucune description.' }}</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                console.log('Initializing map for: {{ $feedingPoint->name }} at {{ $feedingPoint->latitude }}, {{ $feedingPoint->longitude }}');

                var mapContainer = document.getElementById('map');
                if (mapContainer) {
                    var map = L.map('map').setView([{{ $feedingPoint->latitude }}, {{ $feedingPoint->longitude }}], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    L.marker([{{ $feedingPoint->latitude }}, {{ $feedingPoint->longitude }}]).addTo(map)
                        .bindPopup('{{ $feedingPoint->name }}')
                        .openPopup();

                    // Force map resize after a short delay to ensure container is rendered
                    setTimeout(function () {
                        map.invalidateSize();
                    }, 100);
                } else {
                    console.error('Map container not found');
                }
            });
        </script>
    @endpush
@endsection