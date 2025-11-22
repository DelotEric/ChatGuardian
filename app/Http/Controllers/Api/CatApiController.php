<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use Illuminate\Http\JsonResponse;

class CatApiController extends Controller
{
    public function index(): JsonResponse
    {
        $cats = Cat::with(['adoption:id,cat_id,adopter_name,adopted_at', 'currentStay.fosterFamily:id,name'])
            ->withCount([
                'stays',
                'reminders as pending_reminders_count' => fn ($query) => $query->where('status', 'pending'),
            ])
            ->orderBy('name')
            ->paginate(20);

        return response()->json($cats);
    }

    public function show(Cat $cat): JsonResponse
    {
        $cat->load([
            'photos:id,cat_id,path',
            'stays.fosterFamily:id,name',
            'adoption',
            'vetRecords:cat_id,visit_date,reason,amount',
            'reminders' => fn ($query) => $query->orderBy('due_date'),
        ]);

        return response()->json($cat);
    }
}
