@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('members.index') }}"
                    class="text-decoration-none text-muted">Adhérents</a> / {{ $member->full_name }}</p>
            <h1 class="h3 fw-bold">{{ $member->full_name }}</h1>
            <p class="text-muted">{{ $member->member_number }}</p>
        </div>
        <div>
            <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-secondary">Modifier</a>
            <a href="{{ route('memberships.create', ['member_id' => $member->id]) }}" class="btn btn-primary">Ajouter
                cotisation</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Informations</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Email:</strong> {{ $member->email }}</li>
                    @if($member->phone)
                        <li class="list-group-item"><strong>Téléphone:</strong> {{ $member->phone }}</li>
                    @endif
                    @if($member->address)
                        <li class="list-group-item"><strong>Adresse:</strong> {{ $member->address }}, {{ $member->postal_code }}
                            {{ $member->city }}
                        </li>
                    @endif
                    <li class="list-group-item"><strong>Date d'adhésion:</strong> {{ $member->join_date->format('d/m/Y') }}
                    </li>
                    <li class="list-group-item"><strong>Statut:</strong>
                        <span
                            class="badge bg-{{ $member->is_active ? 'success' : 'secondary' }}">{{ $member->is_active ? 'Actif' : 'Inactif' }}</span>
                    </li>
                </ul>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Historique des cotisations</div>
                <div class="list-group list-group-flush">
                    @forelse($member->memberships as $membership)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>Année {{ $membership->year }}</strong> - {{ number_format($membership->amount, 2) }}
                                    €
                                    <br>
                                    <small class="text-muted">Payé le {{ $membership->payment_date->format('d/m/Y') }}</small>
                                    @if($membership->receipt_issued)
                                        <span class="badge bg-success ms-2">Reçu délivré</span>
                                    @endif
                                    @if($membership->receipt_number)
                                        <span class="badge bg-info ms-2">{{ $membership->receipt_number }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="d-flex gap-2">
                                        @if($membership->receipt_number)
                                            <a href="{{ route('memberships.receipt', $membership) }}"
                                                class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="bi bi-file-earmark-pdf"></i> Reçu
                                            </a>
                                        @endif
                                        <a href="{{ route('memberships.edit', $membership) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('memberships.destroy', $membership) }}" method="POST"
                                            onsubmit="return confirm('Supprimer cette cotisation ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-muted text-center py-3">Aucune cotisation</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection