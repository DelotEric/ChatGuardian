<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonorApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Donor::withCount('donations')
            ->withSum('donations as total_donated', 'amount');

        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $donors = $query->orderBy('name')->paginate(25);

        return response()->json($donors);
    }

    public function show(Donor $donor): JsonResponse
    {
        $donor->load(['donations' => fn ($query) => $query->orderByDesc('donated_at')->take(20)])
            ->loadCount('donations')
            ->loadSum('donations as total_donated', 'amount');

        return response()->json($donor);
    }
}
