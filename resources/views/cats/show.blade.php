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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 text-uppercase text-muted mb-0">Identité & santé</h2>
                    @if(in_array($role, ['admin', 'benevole']))
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editCatForm">Modifier</button>
                    @endif
                </div>

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

                @if(in_array($role, ['admin', 'benevole']))
                    <div id="editCatForm" class="collapse mt-4">
                        <hr>
                        <h3 class="h6 fw-semibold mb-3">Mettre à jour le profil</h3>
                        <form class="row g-3" method="POST" action="{{ route('cats.update', $cat) }}">
                            @csrf
                            @method('PATCH')
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $cat->name) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sexe</label>
                                <select name="sex" class="form-select" required>
                                    <option value="male" @selected($cat->sex === 'male')>Mâle</option>
                                    <option value="female" @selected($cat->sex === 'female')>Femelle</option>
                                    <option value="unknown" @selected($cat->sex === 'unknown')>Inconnu</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Naissance</label>
                                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', optional($cat->birthdate)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Statut</label>
                                <select name="status" class="form-select" required>
                                    <option value="free" @selected($cat->status === 'free')>Libre</option>
                                    <option value="foster" @selected($cat->status === 'foster')>En famille d'accueil</option>
                                    <option value="adopted" @selected($cat->status === 'adopted')>Adopté</option>
                                    <option value="deceased" @selected($cat->status === 'deceased')>Décédé</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stérilisé</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="sterilized" id="editSterilized" @checked($cat->sterilized)>
                                    <label class="form-check-label" for="editSterilized">Oui</label>
                                </div>
                                <input type="date" name="sterilized_at" class="form-control mt-1" value="{{ old('sterilized_at', optional($cat->sterilized_at)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vacciné</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="vaccinated" id="editVaccinated" @checked($cat->vaccinated)>
                                    <label class="form-check-label" for="editVaccinated">Oui</label>
                                </div>
                                <input type="date" name="vaccinated_at" class="form-control mt-1" value="{{ old('vaccinated_at', optional($cat->vaccinated_at)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">FIV</label>
                                <select name="fiv_status" class="form-select">
                                    <option value="unknown" @selected($cat->fiv_status === 'unknown')>Inconnu</option>
                                    <option value="positive" @selected($cat->fiv_status === 'positive')>Positif</option>
                                    <option value="negative" @selected($cat->fiv_status === 'negative')>Négatif</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">FELV</label>
                                <select name="felv_status" class="form-select">
                                    <option value="unknown" @selected($cat->felv_status === 'unknown')>Inconnu</option>
                                    <option value="positive" @selected($cat->felv_status === 'positive')>Positif</option>
                                    <option value="negative" @selected($cat->felv_status === 'negative')>Négatif</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Observations, soins, comportement...">{{ old('notes', $cat->notes) }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                @endif
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
            <div>
                <h2 class="h6 text-uppercase text-muted mb-0">Historique des séjours</h2>
                <p class="text-muted small mb-0">Suivi des familles d'accueil et des sorties</p>
            </div>
            <span class="text-muted small">{{ $cat->stays->count() }} séjour(s)</span>
        </div>

        @if(in_array($role, ['admin', 'benevole']))
            <div class="border rounded p-3 bg-light mb-3">
                <h3 class="h6 fw-semibold mb-2">Enregistrer un nouveau séjour</h3>
                @if($families->isEmpty())
                    <p class="text-muted small mb-0">Aucune famille active. Ajoutez-en d'abord depuis l'onglet Familles d'accueil.</p>
                @else
                    <form class="row g-3" method="POST" action="{{ route('cats.stays.store', $cat) }}">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label">Famille d'accueil</label>
                            <select name="foster_family_id" class="form-select" required>
                                <option value="">Choisir...</option>
                                @foreach($families as $family)
                                    <option value="{{ $family->id }}">{{ $family->name }} (capacité {{ $family->capacity }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date d'entrée</label>
                            <input type="date" name="started_at" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date de sortie</label>
                            <input type="date" name="ended_at" class="form-control" placeholder="Optionnel">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Résultat / statut de sortie</label>
                            <input type="text" name="outcome" class="form-control" placeholder="Adopté, remis en liberté...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mettre à jour le statut du chat</label>
                            <select name="next_status" class="form-select">
                                <option value="foster" selected>En famille d'accueil</option>
                                <option value="adopted">Adopté</option>
                                <option value="free">Libre</option>
                                <option value="deceased">Décédé</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Comportement, soins, observations..."></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Ajouter le séjour</button>
                        </div>
                    </form>
                @endif
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Famille d'accueil</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Résultat</th>
                        <th>Notes</th>
                        @if(in_array($role, ['admin', 'benevole']))
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($cat->stays->sortByDesc('started_at') as $stay)
                        <tr>
                            <td>{{ $stay->fosterFamily->name ?? '—' }}</td>
                            <td>{{ $stay->started_at?->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ $stay->ended_at?->format('d/m/Y') ?? 'En cours' }}</td>
                            <td>{{ $stay->outcome ?? '—' }}</td>
                            <td>{{ $stay->notes ?? '—' }}</td>
                            @if(in_array($role, ['admin', 'benevole']))
                                <td class="text-end">
                                    @if(!$stay->ended_at)
                                        <form class="d-flex gap-2 align-items-center justify-content-end flex-wrap" method="POST" action="{{ route('cats.stays.close', [$cat, $stay]) }}">
                                            @csrf
                                            <input type="date" name="ended_at" value="{{ now()->format('Y-m-d') }}" class="form-control form-control-sm w-auto">
                                            <input type="text" name="outcome" class="form-control form-control-sm w-auto" placeholder="Résultat">
                                            <select name="next_status" class="form-select form-select-sm w-auto">
                                                <option value="foster">En famille</option>
                                                <option value="adopted">Adopté</option>
                                                <option value="free">Libre</option>
                                                <option value="deceased">Décédé</option>
                                            </select>
                                            <button class="btn btn-sm btn-outline-primary" type="submit">Clore</button>
                                        </form>
                                    @else
                                        <span class="badge bg-soft-success text-success">Clôturé</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ in_array($role, ['admin', 'benevole']) ? 6 : 5 }}" class="text-center text-muted py-3">Pas encore de séjour enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
