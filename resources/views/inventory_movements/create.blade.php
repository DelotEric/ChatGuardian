@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('inventory-items.index') }}"
                    class="text-decoration-none text-muted">Inventaire</a> / Nouveau mouvement</p>
            <h1 class="h3 fw-bold">Nouveau mouvement de stock</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('inventory-movements.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="inventory_item_id" class="form-label">Article <span class="text-danger">*</span></label>
                        <select class="form-select @error('inventory_item_id') is-invalid @enderror" id="inventory_item_id"
                            name="inventory_item_id" required>
                            <option value="">Sélectionner un article</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('inventory_item_id', $selectedItemId) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} (Stock: {{ number_format($item->quantity, 2) }} {{ $item->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('inventory_item_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Entrée (ajout de stock)</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Sortie (retrait de stock)
                            </option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                            name="quantity" value="{{ old('quantity') }}" step="0.01" min="0.01" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="reason" class="form-label">Raison</label>
                        <input type="text" class="form-control @error('reason') is-invalid @enderror" id="reason"
                            name="reason" value="{{ old('reason') }}" placeholder="Achat, don, usage...">
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="reference" class="form-label">Référence</label>
                        <input type="text" class="form-control @error('reference') is-invalid @enderror" id="reference"
                            name="reference" value="{{ old('reference') }}" placeholder="N° facture, bon...">
                        @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                            rows="3">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('inventory-items.index') }}" class="btn btn-light border me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection