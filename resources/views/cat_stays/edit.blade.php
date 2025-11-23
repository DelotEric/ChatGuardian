@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('cat-stays.index') }}"
                    class="text-decoration-none text-muted">Séjours</a> / Édition</p>
            <h1 class="h4 fw-bold">Modifier le séjour de {{ $catStay->cat->name }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('cat-stays.update', $catStay) }}">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Chat</label>
                    <input type="text" class="form-control" value="{{ $catStay->cat->name }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Famille d'accueil</label>
                    <input type="text" class="form-control" value="{{ $catStay->fosterFamily->name }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de début</label>
                    <input name="started_at" type="date" class="form-control"
                        value="{{ $catStay->started_at->format('Y-m-d') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de fin (si terminé)</label>
                    <input name="ended_at" type="date" class="form-control"
                        value="{{ $catStay->ended_at ? $catStay->ended_at->format('Y-m-d') : '' }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Issue du séjour</label>
                    <input name="outcome" type="text" class="form-control" value="{{ old('outcome', $catStay->outcome) }}"
                        placeholder="Adopté, Transféré, Décédé...">
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $catStay->notes) }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('cat-stays.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection