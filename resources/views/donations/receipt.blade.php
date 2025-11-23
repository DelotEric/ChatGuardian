@extends('layouts.app')

@section('content')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>

    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success no-print">{{ session('status') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger no-print">{{ session('error') }}</div>
        @endif

        <!-- Action buttons -->
        <div class="d-flex gap-2 mb-4 no-print">
            <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('donations.receipt.download', $donation) }}" class="btn btn-primary">
                <i class="bi bi-download"></i> Télécharger PDF
            </a>
            <form action="{{ route('donations.receipt.email', $donation) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-envelope"></i> Envoyer par email
                </button>
            </form>
            <button onclick="window.print()" class="btn btn-outline-primary">
                <i class="bi bi-printer"></i> Imprimer
            </button>
        </div>

        <!-- Receipt content -->
        <div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: #3a7e8c;">REÇU FISCAL</h2>
                    <p class="text-muted">Cerfa n° 11580*05 - Article 200 du Code général des impôts</p>
                </div>

                <div class="row mb-4">
                    <div class="col-6">
                        <h5 class="fw-bold mb-3">Association</h5>
                        <p class="mb-1"><strong>ChatGuardian</strong></p>
                        <p class="mb-1">Association de protection des chats</p>
                        <p class="mb-1">123 Rue de l'Exemple</p>
                        <p class="mb-1">75000 Paris</p>
                        <p class="mb-0">SIREN: 123 456 789</p>
                    </div>
                    <div class="col-6 text-end">
                        <h5 class="fw-bold mb-3">Reçu N°</h5>
                        <p class="h4 text-primary">{{ $donation->receipt_number }}</p>
                        <p class="text-muted">{{ $donation->donated_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Donateur</h5>
                    <p class="mb-1"><strong>{{ $donation->donor->name }}</strong></p>
                    @if($donation->donor->address)
                        <p class="mb-1">{{ $donation->donor->address }}</p>
                        <p class="mb-0">{{ $donation->donor->postal_code }} {{ $donation->donor->city }}</p>
                    @endif
                </div>

                <hr>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Montant du don</h5>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-2">
                            <strong>Montant versé :</strong>
                            <span class="h4 text-primary float-end">{{ number_format($donation->amount, 2) }} €</span>
                        </p>
                        <p class="mb-0 small text-muted">En toutes lettres : {{ $amountInWords }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Calcul de votre avantage fiscal</h5>
                    <div class="alert alert-info">
                        <p class="mb-2"><strong>Réduction d'impôt :</strong>
                            {{ number_format($donation->amount * 0.66, 2) }} €</p>
                        <p class="mb-0 small">Soit 66% du montant de votre don, dans la limite de 20% de votre revenu
                            imposable.</p>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Attestation</h6>
                    <p class="small">
                        L'association certifie sur l'honneur que les dons et versements qu'elle reçoit ouvrent droit à la
                        réduction d'impôt prévue à l'article 200 du Code général des impôts.
                    </p>
                    <p class="small mb-0">
                        Le bénéficiaire reconnaît que le montant indiqué ci-dessus correspond aux sommes versées au titre de
                        l'année {{ $donation->donated_at->year }}, pour lesquelles il n'a pas reçu de contrepartie.
                    </p>
                </div>

                <div class="row mt-5">
                    <div class="col-6">
                        <p class="small mb-0"><strong>Date :</strong> {{ now()->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="small mb-0"><strong>Signature de l'association</strong></p>
                        <p class="small text-muted">(Cachet et signature)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 no-print">
            <small class="text-muted">Conservez ce justificatif, il pourra vous être demandé par l'administration
                fiscale.</small>
        </div>
    </div>
@endsection