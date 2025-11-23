@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('members.index') }}" class="text-decoration-none text-muted">Adhérents</a> / Nouvelle cotisation</p>
            <h1 class="h3 fw-bold">Nouvelle cotisation</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('memberships.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="member_id" class="form-label">Adhérent <span class="text-danger">*</span></label>
                        <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                            <option value="">Sélectionner un adhérent</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ old('member_id', $selectedMemberId) == $m->id ? 'selected' : '' }}>
                                    {{ $m->member_number }} - {{ $m->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="year" class="form-label">Année <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', date('Y')) }}" min="2000" max="2100" required>
                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="amount" class="form-label">Montant (€) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="payment_date" class="form-label">Date de paiement <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="payment_method" class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Chèque</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Virement</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="receipt_number" class="form-label">N° de reçu fiscal</label>
                        <input type="text" class="form-control @error('receipt_number') is-invalid @enderror" id="receipt_number" name="receipt_number" value="{{ old('receipt_number') }}">
                        @error('receipt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="receipt_issued" name="receipt_issued" value="1" {{ old('receipt_issued') ? 'checked' : '' }}>
                            <label class="form-check-label" for="receipt_issued">Reçu fiscal délivré</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('members.index') }}" class="btn btn-light border me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
