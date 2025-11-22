<?php

namespace App\\Http\\Controllers;

use App\\Models\\StockItem;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\Http\\Request;
use Illuminate\\View\\View;

class StockItemController extends Controller
{
    public function index(): View
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $items = StockItem::orderBy('name')->get();
        $lowStockCount = $items->filter->isLow()->count();
        $totalQuantity = $items->sum('quantity');

        return view('stocks.index', [
            'items' => $items,
            'lowStockCount' => $lowStockCount,
            'totalQuantity' => $totalQuantity,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $this->validateData($request);

        StockItem::create($data);

        return redirect()->route('stocks.index')->with('status', 'Article ajouté au stock.');
    }

    public function update(Request $request, StockItem $stockItem): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $data = $this->validateData($request);

        $stockItem->update($data);

        return redirect()->route('stocks.index')->with('status', 'Article mis à jour.');
    }

    public function destroy(StockItem $stockItem): RedirectResponse
    {
        $this->authorizeRoles('admin');

        $stockItem->delete();

        return redirect()->route('stocks.index')->with('status', 'Article supprimé.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:120'],
            'restock_threshold' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        return $data;
    }
}
