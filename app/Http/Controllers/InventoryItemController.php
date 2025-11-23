<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = InventoryItem::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('low_stock')) {
            $query->whereColumn('quantity', '<=', 'min_quantity');
        }

        $items = $query->latest()->paginate(15);

        return view('inventory_items.index', compact('items'));
    }

    public function create(): View
    {
        return view('inventory_items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:food,medicine,equipment,litter,toys,other'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:50'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'min_quantity' => ['required', 'numeric', 'min:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        InventoryItem::create($data);

        return redirect()->route('inventory-items.index')->with('status', 'Article ajouté avec succès.');
    }

    public function show(InventoryItem $inventoryItem): View
    {
        $inventoryItem->load([
            'movements' => function ($query) {
                $query->latest('date')->take(20);
            }
        ]);

        return view('inventory_items.show', compact('inventoryItem'));
    }

    public function edit(InventoryItem $inventoryItem): View
    {
        return view('inventory_items.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:food,medicine,equipment,litter,toys,other'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:50'],
            'min_quantity' => ['required', 'numeric', 'min:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $inventoryItem->update($data);

        return redirect()->route('inventory-items.index')->with('status', 'Article mis à jour avec succès.');
    }

    public function destroy(InventoryItem $inventoryItem): RedirectResponse
    {
        $inventoryItem->delete();

        return redirect()->route('inventory-items.index')->with('status', 'Article supprimé avec succès.');
    }
}
