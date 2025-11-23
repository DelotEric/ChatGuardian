@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('inventory-items.index') }}"
                    class="text-decoration-none text-muted">Inventaire</a> / Nouveau</p>
            <h1 class="h3 fw-bold">Nouvel article</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('inventory-items.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category"
                            required>
                            <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>Nourriture</option>
                            <option value="medicine" {{ old('category') == 'medicine' ? 'selected' : '' }}>Médicaments
                            </option>
                            <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Équipement
                            </option>
                            <option value="litter" {{ old('category') == 'litter' ? 'selected' : '' }}>Litière</option>
                            <option value="toys" {{ old('category') == 'toys' ? 'selected' : '' }}>Jouets</option>
                            <option value="other" {{ old('category', 'other') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="2">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="unit" class="form-label">Unité <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit"
                            value="{{ old('unit') }}" placeholder="kg, pièce, boîte..." required>
                        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantité initiale <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                            name="quantity" value="{{ old('quantity', 0) }}" step="0.01" min="0" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="min_quantity" class="form-label">Seuil minimal <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('min_quantity') is-invalid @enderror"
                            id="min_quantity" name="min_quantity" value="{{ old('min_quantity', 0) }}" step="0.01" min="0"
                            required>
                        @error('min_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="unit_price" class="form-label">Prix unitaire (€)</label>
                        <input type="number" class="form-control @error('unit_price') is-invalid @enderror" id="unit_price"
                            name="unit_price" value="{{ old('unit_price') }}" step="0.01" min="0">
                        @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="storage_location" class="form-label">Emplacement de stockage</label>
                        <input type="text" class="form-control @error('storage_location') is-invalid @enderror"
                            id="storage_location" name="storage_location" value="{{ old('storage_location') }}">
                        @error('storage_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
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