<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FosterFamily;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FosterFamilyApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = FosterFamily::withCount([
            'stays as active_cats_count' => fn ($sub) => $sub->whereNull('ended_at'),
        ]);

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        $families = $query->orderBy('name')->paginate(25);

        $families->getCollection()->transform(function ($family) {
            $family->available_capacity = max($family->capacity - $family->active_cats_count, 0);

            return $family;
        });

        return response()->json($families);
    }
}
