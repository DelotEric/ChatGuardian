@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-muted mb-1">Réseau de soutien</p>
        <h1 class="h4 fw-bold">Bénévoles</h1>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#volunteerModal">Ajouter</button>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Zone</th>
                        <th>Disponibilités</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($volunteers as $volunteer)
                        <tr>
                            <td class="fw-semibold">{{ $volunteer->full_name }}</td>
                            <td>{{ $volunteer->email }}</td>
                            <td>{{ $volunteer->city ?? '—' }}</td>
                            <td>{{ $volunteer->availability ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $volunteer->is_active ? 'bg-soft-success text-success' : 'bg-soft-secondary text-muted' }}">
                                    {{ $volunteer->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucun bénévole encore ajouté.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $volunteers->links() }}
</div>

<div class="modal fade" id="volunteerModal" tabindex="-1" aria-labelledby="volunteerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="volunteerModalLabel">Ajouter un bénévole</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('volunteers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input name="first_name" type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input name="last_name" type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input name="phone" type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ville / zone</label>
                            <input name="city" type="text" class="form-control" placeholder="Quartier / ville">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Disponibilités</label>
                            <input name="availability" type="text" class="form-control" placeholder="Soirs, week-ends...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Compétences</label>
                            <textarea name="skills" class="form-control" rows="2" placeholder="Captures, soins, transport..."></textarea>
                        </div>
                        <div class="col-12 form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                            <label class="form-check-label" for="is_active">Actif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
