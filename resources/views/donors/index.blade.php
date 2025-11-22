@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-muted mb-1">Financeurs</p>
        <h1 class="h4 fw-bold">Donateurs</h1>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#donorForm">Ajouter</button>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Base donateurs</p>
                <h3 class="fw-bold mb-0">{{ $totalDonors }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="text-muted mb-0">Derniers donateurs ajoutés</p>
                    <span class="badge bg-soft-primary text-primary">Récent</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($recentDonors as $donor)
                        <span class="badge bg-light border">{{ $donor->name }}</span>
                    @empty
                        <span class="text-muted">Aucun donateur pour le moment.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div id="donorForm" class="card shadow-sm border-0 collapse mb-4 show">
    <div class="card-body">
        <form class="row g-3" method="POST" action="{{ route('donors.store') }}">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Nom complet</label>
                <input name="name" type="text" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Optionnel">
            </div>
            <div class="col-md-4">
                <label class="form-label">Adresse</label>
                <input name="address" type="text" class="form-control" placeholder="Rue, numéro">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ville</label>
                <input name="city" type="text" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Code postal</label>
                <input name="postal_code" type="text" class="form-control">
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
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
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Dons liés</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donors as $donor)
                        <tr>
                            <td class="fw-semibold">{{ $donor->name }}</td>
                            <td>{{ $donor->email ?? '—' }}</td>
                            <td>
                                <div class="small text-muted">{{ $donor->address }}</div>
                                <div class="small text-muted">{{ $donor->postal_code }} {{ $donor->city }}</div>
                            </td>
                            <td><span class="badge bg-soft-secondary text-muted">{{ $donor->donations_count }} don(s)</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editDonor{{ $donor->id }}">Modifier</button>
                                    <form method="POST" action="{{ route('donors.destroy', $donor) }}" onsubmit="return confirm('Supprimer ce donateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editDonor{{ $donor->id }}" tabindex="-1" aria-labelledby="editDonorLabel{{ $donor->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editDonorLabel{{ $donor->id }}">Modifier le donateur</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form class="row g-3 px-3 pb-3" method="POST" action="{{ route('donors.update', $donor) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Nom complet</label>
                                            <input name="name" type="text" class="form-control" value="{{ $donor->name }}" required>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Email</label>
                                            <input name="email" type="email" class="form-control" value="{{ $donor->email }}" placeholder="Optionnel">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Adresse</label>
                                            <input name="address" type="text" class="form-control" value="{{ $donor->address }}" placeholder="Rue, numéro">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Ville</label>
                                            <input name="city" type="text" class="form-control" value="{{ $donor->city }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">CP</label>
                                            <input name="postal_code" type="text" class="form-control" value="{{ $donor->postal_code }}">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button class="btn btn-primary" type="submit">Mettre à jour</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucun donateur enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $donors->links() }}
</div>
@endsection
