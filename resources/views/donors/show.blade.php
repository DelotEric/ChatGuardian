@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('donors.index') }}"
                    class="text-decoration-none text-muted">Donateurs</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $donor->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('donors.edit', $donor) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('donors.destroy', $donor) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce donateur ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 100px; height: 100px;">
                        <i class="bi bi-person-heart fs-1 text-secondary"></i>
                    </div>
                    <h3 class="h5">{{ $donor->name }}</h3>
                    <p class="text-muted small mb-0">
                        Total des dons : <strong>{{ number_format($donor->donations->sum('amount'), 2, ',', ' ') }}
                            €</strong>
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Coordonnées</div>
                <ul class="list-group list-group-flush">
                    @if($donor->email)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-envelope text-muted me-2"></i>
                            <a href="mailto:{{ $donor->email }}" class="text-decoration-none text-dark">{{ $donor->email }}</a>
                        </li>
                    @endif
                    @if($donor->phone)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-telephone text-muted me-2"></i>
                            <a href="tel:{{ $donor->phone }}" class="text-decoration-none text-dark">{{ $donor->phone }}</a>
                        </li>
                    @endif
                    @if($donor->address || $donor->city)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-geo-alt text-muted me-2"></i>
                            <span>{{ $donor->address }} {{ $donor->postal_code }} {{ $donor->city }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            @if($donor->notes)
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white fw-bold">Notes</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $donor->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Historique des dons</span>
                    <span class="badge bg-secondary">{{ $donor->donations->count() }} dons</span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Paiement</th>
                                <th>Reçu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donor->donations()->latest('donated_at')->get() as $donation)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($donation->donated_at)->translatedFormat('d/m/Y') }}
                                    </td>
                                    <td class="fw-bold">{{ number_format($donation->amount, 2, ',', ' ') }} €</td>
                                    <td>{{ ucfirst($donation->payment_method) }}</td>
                                    <td>
                                        @if($donation->receipt_number)
                                            <span class="badge bg-soft-success text-success">#{{ $donation->receipt_number }}</span>
                                        @else
                                            <span class="badge bg-soft-secondary text-muted">Non</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Aucun don enregistré pour ce donateur.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection