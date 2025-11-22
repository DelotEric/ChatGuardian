<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = StockItem::query();

        if ($request->boolean('low_only')) {
            $query->whereColumn('quantity', '<=', 'restock_threshold');
        }

        $items = $query->orderBy('category')->orderBy('name')->get();
        $items->each->append('is_low');

        return response()->json($items);
    }
}
