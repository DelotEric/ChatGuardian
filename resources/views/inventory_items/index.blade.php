@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold">Inventaire</h1>
        <a href="{{ route('inventory-items.create') }}" class="btn btn-primary">Nouvel article</a>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select class="form-select" name="category" onchange="this.form.submit()">
                        <option value="">Toutes catégories</option>
                        <option value="food" {{ request('category') == 'food' ? 'selected' : '' }}>Nourriture</option>
                        <option value="medicine" {{ request('category') == 'medicine' ? 'selected' : '' }}>Médicaments</option>
                        <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>Équipement</option>
                        <option value="litter" {{ request('category') == 'litter' ? 'selected' : '' }}>Litière</option>
                        <option value="toys" {{ request('category') == 'toys' ? 'selected' : '' }}>Jouets</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="low_stock" id="low_stock" 
                            {{ request()->has('low_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label" for="low_stock">Stock faible uniquement</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Article</th>
                                <th>Catégorie</th>
                                <th>Stock</th>
                                <th>Seuil min</th>
                                <th>Unité</th>
                                <th>Prix unitaire</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="{{ $item->isLowStock() ? 'table-danger' : '' }}">
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                        @if($item->isLowStock())
                                            <span class="badge bg-danger ms-2">Stock faible !</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $categories = [
                                                'food' => ['label' => 'Nourriture', 'color' => 'success'],
                                                'medicine' => ['label' => 'Médicaments', 'color' => 'danger'],
                                                'equipment' => ['label' => 'Équipement', 'color' => 'primary'],
                                                'litter' => ['label' => 'Litière', 'color' => 'warning'],
                                                'toys' => ['label' => 'Jouets', 'color' => 'info'],
                                                'other' => ['label' => 'Autre', 'color' => 'secondary']
                                            ];
                                           $cat = $categories[$item->category] ?? $categories['other'];
                                        @endphp
                                        <span class="badge bg-{{ $cat['color'] }}">{{ $cat['label'] }}</span>
                                    </td>
                                    <td><strong>{{ number_format($item->quantity, 2) }}</strong></td>
                                    <td>{{ number_format($item->min_quantity, 2) }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->unit_price ? number_format($item->unit_price, 2) . ' €' : '-' }}</td>
                                    <td>
                                        <a href="{{ route('inventory-items.show', $item) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                        <a href="{{ route('inventory-items.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $items->links() }}
            @else
                <p class="text-muted text-center py-4">Aucun article dans l'inventaire.</p>
            @endif
        </div>
    </div>
@endsection
