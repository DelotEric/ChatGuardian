<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Services\StockAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function sendAlert(StockAlertService $service): RedirectResponse
    {
        $this->authorizeRoles('admin');

        [$itemsCount, $sentCount] = $service->send();

        if ($itemsCount === 0) {
            return redirect()->route('stocks.index')->with('status', 'Aucun article sous le seuil, aucune alerte envoyée.');
        }

        if ($sentCount === 0) {
            return redirect()->route('stocks.index')->with('status', 'Articles à réapprovisionner, mais aucun destinataire email configuré.');
        }

        return redirect()->route('stocks.index')->with('status', "Alerte envoyée à {$sentCount} destinataire(s) pour {$itemsCount} article(s) faible(s).");
    }

    public function export(): StreamedResponse
    {
        $this->authorizeRoles('admin');

        $filename = 'stocks_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Article', 'Catégorie', 'Quantité', 'Unité', 'Seuil', 'Localisation', 'Notes']);

            StockItem::orderBy('name')->chunk(200, function ($items) use ($handle) {
                foreach ($items as $item) {
                    fputcsv($handle, [
                        $item->name,
                        $item->category,
                        $item->quantity,
                        $item->unit,
                        $item->restock_threshold,
                        $item->location,
                        $item->notes,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
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
