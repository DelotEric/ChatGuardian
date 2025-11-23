@extends('layouts.public')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-primary">Nos chats à l'adoption</h1>
            <p class="text-muted">Trouvez votre futur compagnon parmi nos protégés.</p>
        </div>

        <div class="row g-4">
            @forelse($cats as $cat)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm cat-card">
                        <div class="position-relative">
                            @if($cat->photos->isNotEmpty())
                                <img src="{{ Storage::url($cat->photos->first()->path) }}" class="card-img-top"
                                    alt="{{ $cat->name }}" style="height: 300px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="bi bi-camera fs-1 text-muted"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-3 badge bg-white text-primary shadow-sm">
                                {{ $cat->age_label }}
                            </span>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold mb-2">{{ $cat->name }}</h5>
                            <p class="text-muted small mb-3">
                                {{ $cat->gender_label }} • {{ $cat->breed ?? 'Européen' }}
                            </p>
                            <a href="{{ route('public.cats.show', $cat) }}"
                                class="btn btn-outline-primary rounded-pill px-4 w-100">Rencontrer {{ $cat->name }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5">
                        <i class="bi bi-emoji-smile fs-1 text-muted mb-3 d-block"></i>
                        <h4 class="text-muted">Aucun chat à l'adoption pour le moment.</h4>
                        <p class="text-muted">Revenez bientôt !</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $cats->links() }}
        </div>
    </div>
@endsection