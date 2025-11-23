@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('donations.index') }}"
                    class="text-decoration-none text-muted">Dons</a> / Détails</p>
            <h1 class="h3 fw-bold">Don #{{ $donation->id }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('donations.edit', $donation) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('donations.destroy', $donation) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?');">
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
                        <i class="bi bi-currency-euro fs-1 text-secondary"></i>
                    </div>
                    <h3 class="h2 fw-bold text-primary">{{ number_format($donation->amount, 2, ',', ' ') }} €</h3>
                    <p class="text-muted mb-0">
                        Le {{ \Illuminate\Support\Carbon::parse($donation->donated_at)->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Donateur</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="fw-bold">{{ $donation->donor->name }}</span>
                    </li>
                    @if($donation->donor->email)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-envelope text-muted me-2"></i>
                            <a href="mailto:{{ $donation->donor->email }}"
                                class="text-decoration-none text-dark">{{ $donation->donor->email }}</a>
                        </li>
                    @endif
                    @if($donation->donor->address || $donation->donor->city)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <i class="bi bi-geo-alt text-muted me-2"></i>
                            <span>{{ $donation->donor->address }} {{ $donation->donor->postal_code }}
                                {{ $donation->donor->city }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Détails du paiement</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="text-muted small">Méthode de paiement</label>
                            <p class="fw-semibold">{{ ucfirst($donation->payment_method) }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small">Date d'enregistrement</label>
                            <p class="fw-semibold">{{ $donation->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Reçu fiscal</span>
                    @if($donation->is_receipt_sent)
                        <span class="badge bg-success">Envoyé</span>
                    @else
                        <span class="badge bg-warning text-dark">Non envoyé</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($donation->receipt_number)
                        <p class="mb-3"><strong>Numéro de reçu :</strong> {{ $donation->receipt_number }}</p>
                        <a href="{{ route('donations.receipt', $donation) }}" class="btn btn-primary">
                            <i class="bi bi-file-earmark-text"></i> Générer le reçu fiscal
                        </a>
                    @else
                        <p class="text-muted mb-0">Aucun numéro de reçu attribué.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection