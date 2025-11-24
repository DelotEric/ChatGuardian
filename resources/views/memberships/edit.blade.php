@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('memberships.index') }}"
                    class="text-decoration-none text-muted">Adhésions</a> / Édition</p>
            <h1 class="h4 fw-bold">Modifier l'adhésion #{{ $membership->id }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('memberships.update', $membership) }}">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Adhérent</label>
                    <select name="member_id" class="form-select" required>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id', $membership->member_id) == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Année</label>
                    <input type="number" name="year" class="form-control" min="2000" max="2100"
                        value="{{ old('year', $membership->year) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Montant (€)</label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0"
                        value="{{ old('amount', $membership->amount) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date de paiement</label>
                    <input type="date" name="payment_date" class="form-control"
                        value="{{ old('payment_date', $membership->payment_date->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Moyen de paiement</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash" {{ old('payment_method', $membership->payment_method) == 'cash' ? 'selected' : '' }}>Espèces</option>
                        <option value="check" {{ old('payment_method', $membership->payment_method) == 'check' ? 'selected' : '' }}>Chèque</option>
                        <option value="transfer" {{ old('payment_method', $membership->payment_method) == 'transfer' ? 'selected' : '' }}>Virement</option>
                        <option value="card" {{ old('payment_method', $membership->payment_method) == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                        <option value="other" {{ old('payment_method', $membership->payment_method) == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Numéro de reçu</label>
                    <input type="text" name="receipt_number" class="form-control"
                        value="{{ old('receipt_number', $membership->receipt_number) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $membership->notes) }}</textarea>
                </div>

                <div class="col-12 form-check ms-2">
                    <input type="hidden" name="receipt_issued" value="0">
                    <input class="form-check-input" type="checkbox" value="1" name="receipt_issued" id="receipt_issued" {{ old('receipt_issued', $membership->receipt_issued) ? 'checked' : '' }}>
                    <label class="form-check-label" for="receipt_issued">Reçu fiscal émis</label>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('memberships.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection