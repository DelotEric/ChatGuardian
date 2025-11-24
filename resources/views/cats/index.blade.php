@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1">Suivi des félins</p>
            <h1 class="h4 fw-bold">Chats</h1>
        </div>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#catForm">Ajouter</button>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div id="catForm" class="card shadow-sm border-0 collapse mb-4">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('cats.store') }}">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sexe</label>
                    <select name="sex" class="form-select" required>
                        <option value="male">Mâle</option>
                        <option value="female">Femelle</option>
                        <option value="unknown">Inconnu</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="birthdate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select" required>
                        <option value="free">Libre</option>
                        <option value="foster">En famille d'accueil</option>
                        <option value="adopted">Adopté</option>
                        <option value="deceased">Décédé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stérilisé</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="sterilized" id="sterilized">
                        <label class="form-check-label" for="sterilized">Oui</label>
                    </div>
                    <input type="date" name="sterilized_at" class="form-control mt-1" placeholder="Date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vacciné</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="vaccinated" id="vaccinated">
                        <label class="form-check-label" for="vaccinated">Oui</label>
                    </div>
                    <input type="date" name="vaccinated_at" class="form-control mt-1" placeholder="Date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">FIV</label>
                    <select name="fiv_status" class="form-select">
                        <option value="unknown">Inconnu</option>
                        <option value="positive">Positif</option>
                        <option value="negative">Négatif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">FELV</label>
                    <select name="felv_status" class="form-select">
                        <option value="unknown">Inconnu</option>
                        <option value="positive">Positif</option>
                        <option value="negative">Négatif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Observations, soins..."></textarea>
                </div>
                <div class="col-12 text-end">
                    <button class="btn btn-primary" type="submit">Créer le chat</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Famille actuelle</th>
                            <th>FIV/FELV</th>
                            <th>Dernière mise à jour</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cats as $cat)
                            <tr>
                                <td>
                                    <a href="{{ route('cats.show', $cat) }}" class="text-decoration-none fw-bold text-dark">
                                        {{ $cat->name }}
                                    </a>
                                </td>
                                <td><span class="badge bg-soft-primary text-primary">{{ $cat->status_label }}</span></td>
                                <td>{{ optional($cat->currentStay?->fosterFamily)->name ?? '—' }}</td>
                                <td>{{ $cat->fiv_label }}/{{ $cat->felv_label }}</td>
                                <td class="text-muted small">{{ $cat->updated_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('cats.edit', $cat) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('cats.destroy', $cat) }}" method="POST"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chat ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun chat saisi pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $cats->links() }}
    </div>
@endsection