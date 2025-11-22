<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedingPoint;
use Illuminate\Http\JsonResponse;

class FeedingPointApiController extends Controller
{
    public function index(): JsonResponse
    {
        $points = FeedingPoint::with('volunteers:id,name,phone')
            ->orderBy('name')
            ->get(['id', 'name', 'latitude', 'longitude', 'description']);

        return response()->json($points);
    }
}
