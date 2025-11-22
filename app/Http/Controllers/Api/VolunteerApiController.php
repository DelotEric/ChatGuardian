<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VolunteerApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Volunteer::with('feedingPoints:id,name');

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        $volunteers = $query->orderBy('last_name')->orderBy('first_name')->paginate(25);

        return response()->json($volunteers);
    }
}
