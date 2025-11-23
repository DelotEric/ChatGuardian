<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryMovementController extends Controller
{
    public function index(Request $request): View
    {
        $query = InventoryMovement::with(['inventoryItem', 'user']);

        if ($request->filled('inventory_item_id')) {
            $query->where('inventory_item_id', $request->inventory_item_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->latest('date')->paginate(20);
        $items = InventoryItem::orderBy('name')->get();

        return view('inventory_movements.index', compact('movements', 'items'));
    }

    public function create(Request $request): View
    {
        $items = InventoryItem::orderBy('name')->get();
        $selectedItemId = $request->get('inventory_item_id');

        return view('inventory_movements.create', compact('items', 'selectedItemId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'inventory_item_id' => ['required', 'exists:inventory_items,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'reason' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['user_id'] = auth()->id();

        InventoryMovement::create($data);

        return redirect()->route('inventory-items.show', $data['inventory_item_id'])
            ->with('status', 'Mouvement enregistré avec succès.');
    }

    public function show(InventoryMovement $inventoryMovement): View
    {
        $inventoryMovement->load(['inventoryItem', 'user']);
        return view('inventory_movements.show', compact('inventoryMovement'));
    }
}
