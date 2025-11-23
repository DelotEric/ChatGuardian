@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Bienvenue à l'École du Chat</h1>
            <p class="lead mb-5 opacity-75">Nous sauvons, soignons et proposons à l'adoption les chats abandonnés de la Val
                d'Orge.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('public.cats') }}"
                    class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary">Adopter un chat</a>
                <a href="#donate" class="btn btn-outline-light btn-lg rounded-pill px-5">Faire un don</a>
            </div>
        </div>
    </section>

    <!-- Featured Cats -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">Nos protégés à l'adoption</h2>
                <p class="text-muted">Ils n'attendent que vous pour une nouvelle vie.</p>
            </div>

            <div class="row g-4">
                @forelse($featuredCats as $cat)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm cat-card">
                            @if($cat->photos->isNotEmpty())
                                <img src="{{ Storage::url($cat->photos->first()->path) }}" class="card-img-top"
                                    alt="{{ $cat->name }}" style="height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="bi bi-camera fs-1 text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold mb-1">{{ $cat->name }}</h5>
                                <p class="text-muted small mb-3">
                                    {{ $cat->age_label }} • {{ $cat->gender_label }}
                                </p>
                                <a href="{{ route('public.cats.show', $cat) }}"
                                    class="btn btn-outline-primary rounded-pill px-4">Rencontrer</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Aucun chat à l'adoption pour le moment.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('public.cats') }}" class="btn btn-link text-decoration-none fw-bold">Voir tous nos chats
                    <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- News & Events -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-5">
                <!-- News -->
                <div class="col-lg-6">
                    <h3 class="fw-bold mb-4"><i class="bi bi-newspaper text-primary me-2"></i> Dernières actualités</h3>
                    @forelse($latestNews as $news)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <small class="text-muted d-block mb-1">{{ $news->publish_date->format('d/m/Y') }}</small>
                                <h5 class="fw-bold mb-2">{{ $news->title }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($news->content, 100) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aucune actualité récente.</p>
                    @endforelse
                </div>

                <!-- Events -->
                <div class="col-lg-6">
                    <h3 class="fw-bold mb-4"><i class="bi bi-calendar-event text-primary me-2"></i> Agenda</h3>
                    @forelse($upcomingEvents as $event)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-primary text-white rounded p-3 text-center me-3" style="min-width: 80px;">
                                    <span class="d-block h4 fw-bold mb-0">{{ $event->event_date->format('d') }}</span>
                                    <span class="small text-uppercase">{{ $event->event_date->format('M') }}</span>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $event->title }}</h5>
                                    <p class="mb-0 small text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                                        @if($event->location)
                                            • <i class="bi bi-geo-alt me-1"></i> {{ $event->location }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aucun événement à venir.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Donation -->
    <section class="py-5 bg-primary text-white text-center" id="donate">
        <div class="container">
            <h2 class="fw-bold mb-3">Aidez-nous à les sauver</h2>
            <p class="lead mb-4 opacity-75">Vos dons sont essentiels pour financer les soins, la nourriture et l'hébergement
                de nos protégés.</p>
            <a href="#" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary">Faire un don</a>
            <p class="small mt-3 opacity-50">Déductible des impôts à 66%</p>
        </div>
    </section>
@endsection