@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('inventory-items.index') }}"
                    class="text-decoration-none text-muted">Inventaire</a> / {{ $inventoryItem->name }}</p>
            <h1 class="h3 fw-bold">{{ $inventoryItem->name }}</h1>
        </div>
        <div>
            <a href="{{ route('inventory-items.edit', $inventoryItem) }}" class="btn btn-outline-secondary">Modifier</a>
            <a href="{{ route('inventory-movements.create', ['inventory_item_id' => $inventoryItem->id]) }}"
                class="btn btn-primary">Ajouter mouvement</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Détails de l'article</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Catégorie:</strong> {{ ucfirst($inventoryItem->category) }}</li>
                    @if($inventoryItem->description)
                        <li class="list-group-item"><strong>Description:</strong> {{ $inventoryItem->description }}</li>
                    @endif
                    <li class="list-group-item"><strong>Stock actuel:</strong> <span
                            class="{{ $inventoryItem->isLowStock() ? 'text-danger fw-bold' : '' }}">{{ number_format($inventoryItem->quantity, 2) }}
                            {{ $inventoryItem->unit }}</span></li>
                    <li class="list-group-item"><strong>Seuil minimal:</strong>
                        {{ number_format($inventoryItem->min_quantity, 2) }} {{ $inventoryItem->unit }}</li>
                    @if($inventoryItem->unit_price)
                        <li class="list-group-item"><strong>Prix unitaire:</strong>
                            {{ number_format($inventoryItem->unit_price, 2) }} €</li>
                    @endif
                    @if($inventoryItem->storage_location)
                        <li class="list-group-item"><strong>Emplacement:</strong> {{ $inventoryItem->storage_location }}</li>
                    @endif
                </ul>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Historique des mouvements</div>
                <div class="list-group list-group-flush">
                    @forelse($inventoryItem->movements as $movement)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-{{ $movement->type == 'in' ? 'success' : 'danger' }}">
                                        {{ $movement->type == 'in' ? 'Entrée' : 'Sortie' }}
                                    </span>
                                    <strong>{{ number_format($movement->quantity, 2) }} {{ $inventoryItem->unit }}</strong>
                                    @if($movement->reason)
                                        - {{ $movement->reason }}
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $movement->date->format('d/m/Y') }} par
                                        {{ $movement->user->name ?? 'Système' }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-muted text-center py-3">Aucun mouvement</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection