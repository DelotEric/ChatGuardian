<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;

class DonationApiController extends Controller
{
    public function index(): JsonResponse
    {
        $donations = Donation::with('donor:id,name,email')
            ->select('id', 'donor_id', 'amount', 'donated_at', 'payment_method', 'receipt_number', 'is_receipt_sent')
            ->orderByDesc('donated_at')
            ->paginate(25);

        return response()->json($donations);
    }

    public function show(Donation $donation): JsonResponse
    {
        $donation->load('donor:id,name,email,address,city,postal_code');

        return response()->json($donation);
    }
}
