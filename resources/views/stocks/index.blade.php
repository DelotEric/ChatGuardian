@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Stocks & fournitures</h1>
            <p class="text-muted mb-0">Suivi des croquettes, litière, médicaments et matériel.</p>
        </div>
        @if(auth()->user()->role === 'admin')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItemModal">+ Ajouter</button>
        @endif
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Articles suivis</p>
                    <h3 class="mb-0">{{ $items->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Quantité totale</p>
                    <h3 class="mb-0">{{ $totalQuantity }} unités</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 {{ $lowStockCount ? 'border-warning' : '' }}">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Seuil d'alerte</p>
                        <h3 class="mb-0">{{ $lowStockCount }} à surveiller</h3>
                    </div>
                    <span class="badge {{ $lowStockCount ? 'bg-warning text-dark' : 'bg-success' }}">{{ $lowStockCount ? 'A réapprovisionner' : 'OK' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h5 mb-0">Inventaire</h2>
                    <small class="text-muted">Classement par nom d'article</small>
                </div>
                @if(auth()->user()->role === 'admin')
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createItemModal">Ajouter un article</button>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                            <th>Seuil</th>
                            <th>Localisation</th>
                            <th>Notes</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="text-end">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="{{ $item->isLow() ? 'table-warning' : '' }}">
                                <td class="fw-semibold">{{ $item->name }}</td>
                                <td>{{ $item->category ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $item->isLow() ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $item->quantity }} {{ $item->unit }}
                                    </span>
                                </td>
                                <td>{{ $item->restock_threshold }} {{ $item->unit }}</td>
                                <td>{{ $item->location ?? '—' }}</td>
                                <td class="text-muted">{{ $item->notes ?? '—' }}</td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">Modifier</button>
                                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteItemModal{{ $item->id }}">Supprimer</button>
                                        </div>
                                    </td>
                                @endif
                            </tr>

                            @if(auth()->user()->role === 'admin')
                                <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('stocks.update', $item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier {{ $item->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nom</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Catégorie</label>
                                                            <input type="text" name="category" class="form-control" value="{{ $item->category }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Quantité</label>
                                                            <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="0" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Unité</label>
                                                            <input type="text" name="unit" class="form-control" value="{{ $item->unit }}" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Seuil de réappro</label>
                                                            <input type="number" name="restock_threshold" class="form-control" value="{{ $item->restock_threshold }}" min="0" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Localisation</label>
                                                            <input type="text" name="location" class="form-control" value="{{ $item->location }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Notes</label>
                                                            <textarea name="notes" class="form-control" rows="3">{{ $item->notes }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('stocks.destroy', $item) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Supprimer {{ $item->name }} ?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-0">Cette action retirera l'article de l'inventaire. Continuer ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucun article enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
        <div class="modal fade" id="createItemModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('stocks.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Nouvel article</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Catégorie</label>
                                    <input type="text" name="category" class="form-control" placeholder="Croquettes, litière, soins...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="quantity" class="form-control" min="0" value="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Unité</label>
                                    <input type="text" name="unit" class="form-control" value="sac" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Seuil de réappro</label>
                                    <input type="number" name="restock_threshold" class="form-control" min="0" value="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Localisation</label>
                                    <input type="text" name="location" class="form-control" placeholder="Local, garage, véto...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Marque, taille des sacs, médicaments sensibles..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
