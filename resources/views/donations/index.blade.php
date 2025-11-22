@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="text-muted mb-1">Financement</p>
        <h1 class="h4 fw-bold">Dons & reçus</h1>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('donors.index') }}">Gérer les donateurs</a>
        <a class="btn btn-outline-secondary" href="{{ route('donations.export') }}">Exporter CSV</a>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#donationForm">Enregistrer</button>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card border-0 bg-soft-primary mb-3">
    <div class="card-body d-flex align-items-center justify-content-between">
        <div>
            <p class="text-muted mb-0">Total du mois</p>
            <h3 class="mb-0">{{ number_format($totalMonth, 2, ',', ' ') }} €</h3>
        </div>
        <span class="badge bg-primary">Suivi des reçus fiscaux</span>
    </div>
</div>

<div id="donationForm" class="card shadow-sm border-0 collapse mb-4">
    <div class="card-body">
        <form class="row g-3" method="POST" action="{{ route('donations.store') }}">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Donateur</label>
                <select name="donor_id" class="form-select" required>
                    @foreach($donors as $donor)
                        <option value="{{ $donor->id }}">{{ $donor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Montant (€)</label>
                <input name="amount" type="number" step="0.01" min="0" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input name="donated_at" type="date" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Paiement</label>
                <select name="payment_method" class="form-select" required>
                    <option value="card">Carte</option>
                    <option value="transfer">Virement</option>
                    <option value="cash">Espèces</option>
                    <option value="check">Chèque</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">N° reçu</label>
                <input name="receipt_number" type="text" class="form-control" placeholder="Optionnel">
            </div>
            <div class="col-md-4 form-check mt-4">
                <input class="form-check-input" type="checkbox" value="1" name="is_receipt_sent" id="receipt_sent">
                <label class="form-check-label" for="receipt_sent">Reçu envoyé</label>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Enregistrer le don</button>
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
                        <th>Donateur</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Paiement</th>
                        <th>Reçu</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                        <tr>
                            <td class="fw-semibold">{{ $donation->donor->name }}</td>
                            <td>{{ number_format($donation->amount, 2, ',', ' ') }} €</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($donation->donated_at)->translatedFormat('d/m/Y') }}</td>
                            <td>{{ ucfirst($donation->payment_method) }}</td>
                            <td>
                                @if($donation->receipt_number)
                                    <span class="badge bg-soft-success text-success">#{{ $donation->receipt_number }}</span>
                                @else
                                    <span class="badge bg-soft-secondary text-muted">À générer</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('donations.receipt', $donation) }}">PDF</a>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editDonation{{ $donation->id }}">Modifier</button>
                                    <form method="POST" action="{{ route('donations.destroy', $donation) }}" onsubmit="return confirm('Supprimer ce don ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editDonation{{ $donation->id }}" tabindex="-1" aria-labelledby="editDonationLabel{{ $donation->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editDonationLabel{{ $donation->id }}">Modifier le don</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form class="row g-3 px-3 pb-3" method="POST" action="{{ route('donations.update', $donation) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Donateur</label>
                                            <select name="donor_id" class="form-select" required>
                                                @foreach($donors as $donor)
                                                    <option value="{{ $donor->id }}" @selected($donor->id === $donation->donor_id)>{{ $donor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Montant (€)</label>
                                            <input name="amount" type="number" step="0.01" min="0" class="form-control" value="{{ $donation->amount }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Date</label>
                                            <input name="donated_at" type="date" class="form-control" value="{{ optional($donation->donated_at)->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Paiement</label>
                                            <select name="payment_method" class="form-select" required>
                                                @foreach(['card' => 'Carte', 'transfer' => 'Virement', 'cash' => 'Espèces', 'check' => 'Chèque'] as $value => $label)
                                                    <option value="{{ $value }}" @selected($donation->payment_method === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">N° reçu</label>
                                            <input name="receipt_number" type="text" class="form-control" value="{{ $donation->receipt_number }}" placeholder="Optionnel">
                                        </div>
                                        <div class="col-md-6 form-check mt-4">
                                            <input class="form-check-input" type="checkbox" value="1" name="is_receipt_sent" id="receipt_sent_{{ $donation->id }}" {{ $donation->is_receipt_sent ? 'checked' : '' }}>
                                            <label class="form-check-label" for="receipt_sent_{{ $donation->id }}">Reçu envoyé</label>
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
                            <td colspan="6" class="text-center text-muted py-4">Aucun don enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $donations->links() }}
</div>
@endsection
