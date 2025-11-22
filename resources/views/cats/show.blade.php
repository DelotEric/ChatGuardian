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

    <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('cats.profile', $cat) }}">📄 Fiche PDF</a>
    </div>
</div>

<div class="col-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 text-uppercase text-muted mb-0">Adoption</h2>
                    @if($cat->adoption)
                        <span class="badge bg-soft-success text-success">Enregistrée</span>
                    @else
                        <span class="badge bg-soft-secondary text-secondary">A enregistrer</span>
                    @endif
                </div>

                @if($cat->adoption)
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Adoptant</p>
                        <p class="fw-semibold mb-0">{{ $cat->adoption->adopter_name }}</p>
                        <p class="text-muted mb-0">{{ $cat->adoption->adopter_email }} · {{ $cat->adoption->adopter_phone }}</p>
                        <p class="text-muted mb-0">{{ $cat->adoption->adopter_address }}</p>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Date d'adoption</p>
                            <p class="fw-semibold mb-0">{{ $cat->adoption->adopted_at?->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Participation</p>
                            <p class="fw-semibold mb-0">{{ number_format($cat->adoption->fee, 2, ',', ' ') }} €</p>
                        </div>
                    </div>
                    @if($cat->adoption->notes)
                        <p class="text-muted small mb-1">Notes</p>
                        <p class="mb-3">{{ $cat->adoption->notes }}</p>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('cats.adoptions.contract', [$cat, $cat->adoption]) }}" class="btn btn-outline-primary btn-sm">Télécharger le contrat</a>
                        @if($role === 'admin')
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#editAdoption">Modifier</button>
                            <form method="POST" action="{{ route('cats.adoptions.destroy', [$cat, $cat->adoption]) }}" onsubmit="return confirm('Supprimer l\'adoption ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                            </form>
                        @endif
                    </div>

                    @if($role === 'admin')
                        <div id="editAdoption" class="collapse">
                            <hr>
                            <h3 class="h6 fw-semibold mb-3">Mettre à jour l'adoption</h3>
                            <form class="row g-3" method="POST" action="{{ route('cats.adoptions.update', [$cat, $cat->adoption]) }}">
                                @csrf
                                @method('PATCH')
                                <div class="col-md-6">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" name="adopter_name" class="form-control" value="{{ $cat->adoption->adopter_name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="adopter_email" class="form-control" value="{{ $cat->adoption->adopter_email }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="adopter_phone" class="form-control" value="{{ $cat->adoption->adopter_phone }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Adresse</label>
                                    <input type="text" name="adopter_address" class="form-control" value="{{ $cat->adoption->adopter_address }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date d'adoption</label>
                                    <input type="date" name="adopted_at" class="form-control" value="{{ optional($cat->adoption->adopted_at)->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Participation (€)</label>
                                    <input type="number" step="0.01" min="0" name="fee" class="form-control" value="{{ $cat->adoption->fee }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2">{{ $cat->adoption->notes }}</textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    @endif
                @elseif($role === 'admin')
                    <div class="alert alert-light">Aucune adoption enregistrée pour ce chat. Renseignez l'adoptant et générez un contrat.</div>
                    <form class="row g-3" method="POST" action="{{ route('cats.adoptions.store', $cat) }}">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">Nom complet</label>
                            <input type="text" name="adopter_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="adopter_email" class="form-control" placeholder="optionnel">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="adopter_phone" class="form-control" placeholder="optionnel">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adopter_address" class="form-control" placeholder="Rue, ville">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date d'adoption</label>
                            <input type="date" name="adopted_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Participation (€)</label>
                            <input type="number" step="0.01" min="0" name="fee" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Conditions, suivi..."></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Enregistrer l'adoption</button>
                        </div>
                    </form>
                @else
                    <p class="text-muted mb-0">Aucune adoption enregistrée.</p>
                @endif
            </div>
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
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
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
        @php
            $pendingReminders = $reminders->where('status', 'pending');
            $completedReminders = $reminders->where('status', 'done');
        @endphp
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h6 text-uppercase text-muted mb-0">Rappels & suivis</h2>
                <p class="text-muted small mb-0">Vaccins, visites, contrôles à planifier</p>
            </div>
            <div class="text-end">
                <span class="badge bg-soft-warning text-warning me-2">{{ $pendingReminders->count() }} à faire</span>
                <span class="badge bg-soft-success text-success">{{ $completedReminders->count() }} terminé(s)</span>
            </div>
        </div>

        @if(in_array($role, ['admin', 'benevole']))
            <div class="border rounded p-3 bg-light mb-3">
                <h3 class="h6 fw-semibold mb-2">Programmer un rappel</h3>
                <form class="row g-3" method="POST" action="{{ route('cats.reminders.store', $cat) }}">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control" placeholder="Rappel vaccin, contrôle..." required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Échéance</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="vet">Visite véto</option>
                            <option value="vaccine">Vaccin</option>
                            <option value="adoption_followup">Suivi adoption</option>
                            <option value="health">Suivi santé</option>
                            <option value="admin">Administratif</option>
                            <option value="other" selected>Autre</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Notes (optionnel)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Détail du rappel, coordonnées de la clinique..."></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Ajouter le rappel</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Échéance</th>
                        <th>Type</th>
                        <th>Notes</th>
                        <th>Statut</th>
                        @if(in_array($role, ['admin', 'benevole']))
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($reminders as $reminder)
                        <tr @class(['table-warning' => $reminder->status === 'pending' && $reminder->due_date->isPast()])>
                            <td class="fw-semibold">{{ $reminder->title }}</td>
                            <td>{{ $reminder->due_date?->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $labels = [
                                        'vet' => 'Visite véto',
                                        'vaccine' => 'Vaccin',
                                        'adoption_followup' => 'Suivi adoption',
                                        'health' => 'Suivi santé',
                                        'admin' => 'Administratif',
                                        'other' => 'Autre',
                                    ];
                                @endphp
                                <span class="badge bg-soft-secondary text-secondary">{{ $labels[$reminder->type] ?? $reminder->type }}</span>
                            </td>
                            <td class="text-muted small">{{ $reminder->notes ?: '—' }}</td>
                            <td>
                                @if($reminder->status === 'pending')
                                    <span class="badge bg-soft-warning text-warning">À faire</span>
                                @else
                                    <span class="badge bg-soft-success text-success">Terminé</span>
                                @endif
                            </td>
                            @if(in_array($role, ['admin', 'benevole']))
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @if($reminder->status === 'pending')
                                            <form method="POST" action="{{ route('cats.reminders.complete', [$cat, $reminder]) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success" type="submit">Marquer fait</button>
                                            </form>
                                        @endif
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#editReminder{{ $reminder->id }}">Éditer</button>
                                        <form method="POST" action="{{ route('cats.reminders.destroy', [$cat, $reminder]) }}" onsubmit="return confirm('Supprimer ce rappel ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                        </form>
                                    </div>
                                    <div id="editReminder{{ $reminder->id }}" class="collapse mt-2">
                                        <form class="row g-2 border rounded p-3" method="POST" action="{{ route('cats.reminders.update', [$cat, $reminder]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="col-md-4">
                                                <input type="text" name="title" class="form-control" value="{{ $reminder->title }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" name="due_date" class="form-control" value="{{ $reminder->due_date?->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="type" class="form-select" required>
                                                    @foreach($labels as $value => $label)
                                                        <option value="{{ $value }}" @selected($reminder->type === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="status" class="form-select" required>
                                                    <option value="pending" @selected($reminder->status === 'pending')>À faire</option>
                                                    <option value="done" @selected($reminder->status === 'done')>Terminé</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <textarea name="notes" class="form-control" rows="2" placeholder="Notes">{{ $reminder->notes }}</textarea>
                                            </div>
                                            <div class="col-12 text-end">
                                                <button class="btn btn-primary btn-sm" type="submit">Mettre à jour</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ in_array($role, ['admin', 'benevole']) ? 6 : 5 }}" class="text-center text-muted py-3">Aucun rappel enregistré pour ce chat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        @php($totalVetCosts = $cat->vetRecords->sum('amount'))
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h6 text-uppercase text-muted mb-0">Suivi vétérinaire</h2>
                <p class="text-muted small mb-0">Interventions, soins et pièces jointes</p>
            </div>
            <div class="text-end">
                <span class="badge bg-soft-primary text-primary me-2">{{ $cat->vetRecords->count() }} visite(s)</span>
                <span class="badge bg-soft-success text-success">Total {{ number_format($totalVetCosts, 2, ',', ' ') }} €</span>
                @if(in_array($role, ['admin', 'benevole']))
                    <div class="mt-2">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('cats.vet-records.export', $cat) }}">📄 Export CSV</a>
                    </div>
                @endif
            </div>
        </div>

        @if(in_array($role, ['admin', 'benevole']))
            <div class="border rounded p-3 bg-light mb-3">
                <h3 class="h6 fw-semibold mb-2">Ajouter une visite</h3>
                <form class="row g-3" method="POST" action="{{ route('cats.vet-records.store', $cat) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="visit_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Clinique / vétérinaire</label>
                        <input type="text" name="clinic_name" class="form-control" placeholder="Nom ou ville">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Motif</label>
                        <input type="text" name="reason" class="form-control" required placeholder="Vaccin, contrôle, soins...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Montant (€)</label>
                        <input type="number" step="0.01" min="0" name="amount" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Diagnostic, traitement, rappel...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Document (PDF, image)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf,image/*">
                        <small class="text-muted">4 Mo max.</small>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Enregistrer la visite</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Motif</th>
                        <th>Clinique</th>
                        <th class="text-end">Montant</th>
                        <th>Document</th>
                        <th>Notes</th>
                        @if(in_array($role, ['admin', 'benevole']))
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($cat->vetRecords->sortByDesc('visit_date') as $record)
                        <tr>
                            <td>{{ $record->visit_date?->format('d/m/Y') }}</td>
                            <td class="fw-semibold">{{ $record->reason }}</td>
                            <td>{{ $record->clinic_name ?? '—' }}</td>
                            <td class="text-end">{{ number_format($record->amount, 2, ',', ' ') }} €</td>
                            <td>
                                @if($record->document_path)
                                    <a class="text-decoration-none" href="{{ Storage::url($record->document_path) }}" target="_blank">Voir</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $record->notes ?? '—' }}</td>
                            @if(in_array($role, ['admin', 'benevole']))
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" type="button" data-bs-toggle="collapse" data-bs-target="#vet-edit-{{ $record->id }}">Modifier</button>
                                    <form class="d-inline" method="POST" action="{{ route('cats.vet-records.destroy', [$cat, $record]) }}" onsubmit="return confirm('Supprimer cette visite ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                        @if(in_array($role, ['admin', 'benevole']))
                            <tr class="collapse bg-light" id="vet-edit-{{ $record->id }}">
                                <td colspan="7" class="p-3">
                                    <form class="row g-3 align-items-end" method="POST" action="{{ route('cats.vet-records.update', [$cat, $record]) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="col-md-2">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="visit_date" class="form-control form-control-sm" value="{{ optional($record->visit_date)->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Clinique</label>
                                            <input type="text" name="clinic_name" class="form-control form-control-sm" value="{{ $record->clinic_name }}" placeholder="Nom ou ville">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Motif</label>
                                            <input type="text" name="reason" class="form-control form-control-sm" value="{{ $record->reason }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Montant (€)</label>
                                            <input type="number" step="0.01" min="0" name="amount" class="form-control form-control-sm" value="{{ $record->amount }}">
                                        </div>
        
                                        <div class="col-md-4">
                                            <label class="form-label">Notes</label>
                                            <input type="text" name="notes" class="form-control form-control-sm" value="{{ $record->notes }}" placeholder="Médicaments, rappel...">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Document (remplacer)</label>
                                            <input type="file" name="document" class="form-control form-control-sm" accept=".pdf,image/*">
                                        </div>
                                        <div class="col-md-2 ms-auto text-end">
                                            <button class="btn btn-sm btn-primary" type="submit">Mettre à jour</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ in_array($role, ['admin', 'benevole']) ? 7 : 6 }}" class="text-center text-muted py-3">Aucune visite enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <p class="text-muted mb-1">Traçabilité</p>
                <h3 class="h6 fw-semibold mb-0">Journal du chat</h3>
            </div>
            <span class="badge bg-soft-primary text-primary">Historique</span>
        </div>
        <div class="list-group list-group-flush">
            @forelse($activities as $activity)
                <div class="list-group-item px-0 d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-soft-secondary text-muted d-flex align-items-center justify-content-center"
                         style="width: 42px; height: 42px;">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-1 fw-semibold text-capitalize">{{ str_replace('.', ' · ', $activity->action) }}</p>
                            <small class="text-muted">{{ optional($activity->created_at)->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 text-muted">{{ $activity->description ?? 'Mise à jour enregistrée.' }}</p>
                        <small class="text-muted">Par {{ optional($activity->user)->name ?? 'Système' }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">Aucune activité enregistrée pour ce chat.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
