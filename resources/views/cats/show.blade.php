@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $role = auth()->user()->role ?? null;
@endphp
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-muted mb-1">Fiche chat</p>
        <h1 class="h4 fw-bold">{{ $cat->name }}</h1>
        <div class="d-flex gap-2 align-items-center mt-1">
            <span class="badge bg-soft-primary text-primary">{{ ucfirst($cat->status) }}</span>
            <span class="text-muted small">Dernière mise à jour {{ $cat->updated_at?->diffForHumans() }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('cats.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-warning">{{ session('error') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-muted mb-3">Identité & santé</h2>
                <div class="d-flex flex-wrap gap-3">
                    <div>
                        <p class="text-muted small mb-1">Sexe</p>
                        <span class="fw-semibold">{{ ucfirst($cat->sex) }}</span>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Naissance</p>
                        <span class="fw-semibold">{{ $cat->birthdate?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Stérilisé</p>
                        <span class="fw-semibold">{{ $cat->sterilized ? 'Oui' : 'Non' }} @if($cat->sterilized_at) <span class="text-muted">({{ $cat->sterilized_at->format('d/m/Y') }})</span>@endif</span>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Vacciné</p>
                        <span class="fw-semibold">{{ $cat->vaccinated ? 'Oui' : 'Non' }} @if($cat->vaccinated_at) <span class="text-muted">({{ $cat->vaccinated_at->format('d/m/Y') }})</span>@endif</span>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-muted small mb-1">FIV / FELV</p>
                    <span class="badge bg-light text-dark">FIV: {{ ucfirst($cat->fiv_status) }}</span>
                    <span class="badge bg-light text-dark ms-2">FELV: {{ ucfirst($cat->felv_status) }}</span>
                </div>
                <div class="mt-3">
                    <p class="text-muted small mb-1">Notes</p>
                    <p class="mb-0">{{ $cat->notes ?: '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 text-uppercase text-muted mb-0">Galerie (max 3)</h2>
                    <span class="text-muted small">{{ $cat->photos->count() }}/3</span>
                </div>

                @if(in_array($role, ['admin', 'benevole']))
                    <form class="border rounded p-3 mb-3 bg-light" method="POST" action="{{ route('cats.photos.store', $cat) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Ajouter des photos</label>
                            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required>
                            <small class="text-muted">3 Mo max par image, 3 photos maximum par chat.</small>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Légendes (optionnelles)</label>
                            <input type="text" name="captions[]" class="form-control mb-2" placeholder="Première photo">
                            <input type="text" name="captions[]" class="form-control mb-2" placeholder="Deuxième photo">
                            <input type="text" name="captions[]" class="form-control" placeholder="Troisième photo">
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">Téléverser</button>
                        </div>
                    </form>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="row g-3">
                    @forelse($cat->photos as $photo)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                @php
                                    $photoUrl = Str::startsWith($photo->path, ['http://', 'https://', '/', 'images/'])
                                        ? asset($photo->path)
                                        : Storage::url($photo->path);
                                @endphp
                                <img src="{{ $photoUrl }}" class="card-img-top" alt="Photo de {{ $cat->name }}">
                                <div class="card-body p-2">
                                    <p class="small mb-1">{{ $photo->caption ?: 'Photo' }}</p>
                                    @if(in_array($role, ['admin', 'benevole']))
                                        <form method="POST" action="{{ route('cats.photos.destroy', [$cat, $photo]) }}" onsubmit="return confirm('Supprimer cette photo ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger w-100" type="submit">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">
                            <p class="mb-2">Aucune photo pour le moment.</p>
                            <img src="{{ asset('images/cat-placeholder.svg') }}" class="img-fluid" style="max-width: 240px;" alt="Placeholder chat">
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h6 text-uppercase text-muted mb-0">Historique des séjours</h2>
            <span class="text-muted small">{{ $cat->stays->count() }} séjour(s)</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Famille d'accueil</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Résultat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cat->stays as $stay)
                        <tr>
                            <td>{{ $stay->fosterFamily->name ?? '—' }}</td>
                            <td>{{ $stay->started_at?->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ $stay->ended_at?->format('d/m/Y') ?? 'En cours' }}</td>
                            <td>{{ $stay->outcome ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Pas encore de séjour enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
