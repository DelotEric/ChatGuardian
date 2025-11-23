@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('cat-stays.index') }}"
                    class="text-decoration-none text-muted">Séjours</a> / Nouveau</p>
            <h1 class="h4 fw-bold">Placer un chat en famille d'accueil</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('cat-stays.store') }}">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Chat à placer</label>
                    <select name="cat_id" class="form-select" required>
                        <option value="">Choisir un chat...</option>
                        @foreach($cats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Seuls les chats qui ne sont pas déjà en famille d'accueil apparaissent ici.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Famille d'accueil</label>
                    <select name="foster_family_id" class="form-select" required>
                        <option value="">Choisir une famille...</option>
                        @foreach($families as $family)
                            <option value="{{ $family->id }}">
                                {{ $family->name }} ({{ $family->city }}) - Capacité: {{ $family->capacity }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de début</label>
                    <input name="started_at" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                        placeholder="Conditions particulières, besoins spécifiques..."></textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('cat-stays.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary" type="submit">Valider le placement</button>
                </div>
            </form>
        </div>
    </div>
@endsection