@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('donations.index') }}"
                    class="text-decoration-none text-muted">Dons</a> / Édition</p>
            <h1 class="h4 fw-bold">Modifier le don #{{ $donation->id }}</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form class="row g-3" method="POST" action="{{ route('donations.update', $donation) }}">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label">Donateur</label>
                    <select name="donor_id" class="form-select" required>
                        @foreach(\App\Models\Donor::orderBy('name')->get() as $donor)
                            <option value="{{ $donor->id }}" {{ $donation->donor_id == $donor->id ? 'selected' : '' }}>
                                {{ $donor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Montant (€)</label>
                    <input name="amount" type="number" step="0.01" min="0" class="form-control"
                        value="{{ old('amount', $donation->amount) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input name="donated_at" type="date" class="form-control"
                        value="{{ old('donated_at', \Illuminate\Support\Carbon::parse($donation->donated_at)->format('Y-m-d')) }}"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Paiement</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="card" {{ old('payment_method', $donation->payment_method) === 'card' ? 'selected' : '' }}>Carte</option>
                        <option value="transfer" {{ old('payment_method', $donation->payment_method) === 'transfer' ? 'selected' : '' }}>Virement</option>
                        <option value="cash" {{ old('payment_method', $donation->payment_method) === 'cash' ? 'selected' : '' }}>Espèces</option>
                        <option value="check" {{ old('payment_method', $donation->payment_method) === 'check' ? 'selected' : '' }}>Chèque</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">N° reçu</label>
                    <input name="receipt_number" type="text" class="form-control"
                        value="{{ old('receipt_number', $donation->receipt_number) }}" placeholder="Optionnel">
                </div>
                <div class="col-md-4 form-check mt-4">
                    <input class="form-check-input" type="checkbox" value="1" name="is_receipt_sent" id="receipt_sent" {{ old('is_receipt_sent', $donation->is_receipt_sent) ? 'checked' : '' }}>
                    <label class="form-check-label" for="receipt_sent">Reçu envoyé</label>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection