@extends('layouts.public')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.cats') }}">Nos Chats</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $cat->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Photos Column -->
            <div class="col-lg-6">
                @if($cat->photos->isNotEmpty())
                    <div id="catCarousel" class="carousel slide shadow rounded overflow-hidden" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($cat->photos as $key => $photo)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($photo->path) }}" class="d-block w-100" alt="{{ $cat->name }}"
                                        style="height: 500px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if($cat->photos->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#catCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#catCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        @endif
                    </div>
                    <div class="row g-2 mt-2">
                        @foreach($cat->photos as $key => $photo)
                            <div class="col-3">
                                <img src="{{ Storage::url($photo->path) }}" class="img-fluid rounded cursor-pointer"
                                    style="height: 80px; object-fit: cover; cursor: pointer;"
                                    onclick="document.querySelector('#catCarousel').querySelector('.carousel-item.active').classList.remove('active'); document.querySelectorAll('#catCarousel .carousel-item')[{{ $key }}].classList.add('active');">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm"
                        style="height: 500px;">
                        <i class="bi bi-camera fs-1 text-muted"></i>
                    </div>
                @endif
            </div>

            <!-- Details Column -->
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-2">{{ $cat->name }}</h1>
                <div class="d-flex gap-2 mb-4">
                    <span class="badge bg-primary fs-6">{{ $cat->age_label }}</span>
                    <span class="badge bg-info text-dark fs-6">{{ $cat->gender_label }}</span>
                    @if($cat->breed)
                        <span class="badge bg-secondary fs-6">{{ $cat->breed }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold text-primary">Son histoire / Caractère</h5>
                    <p class="lead text-muted" style="white-space: pre-line;">
                        {{ $cat->description ?? 'Aucune description disponible pour le moment.' }}</p>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded h-100">
                            <h6 class="fw-bold"><i class="bi bi-heart-pulse text-danger me-2"></i> Santé</h6>
                            <p class="mb-0 small">{{ $cat->health_status ?? 'Non spécifié' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded h-100">
                            <h6 class="fw-bold"><i class="bi bi-geo-alt text-primary me-2"></i> Localisation</h6>
                            <p class="mb-0 small">Visible en famille d'accueil (Val d'Orge)</p>
                        </div>
                    </div>
                </div>

                <div class="card border-primary bg-soft-primary">
                    <div class="card-body text-center p-4">
                        <h4 class="fw-bold mb-3">Coup de cœur pour {{ $cat->name }} ?</h4>
                        <p class="mb-4">Remplissez notre formulaire de candidature en ligne. C'est sans engagement et cela
                            nous permet de mieux vous connaître.</p>
                        <a href="{{ route('public.apply', $cat) }}"
                            class="btn btn-primary btn-lg rounded-pill px-5 w-100 fw-bold">
                            Je postule pour l'adopter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection