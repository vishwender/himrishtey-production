<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WalletOffer;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    public function index(): JsonResponse
    {
        $offers = WalletOffer::query()
            ->orderByRaw('CAST(amount AS DECIMAL(12,2)) ASC')
            ->orderBy('id')
            ->get(['id', 'title', 'description', 'amount', 'add_on_percentage', 'final_amount'])
            ->map(fn (WalletOffer $offer): array => [
                'id' => (int) $offer->id,
                'title' => $offer->title,
                'description' => $offer->description,
                'amount' => $offer->amount,
                'add_on_percentage' => $offer->add_on_percentage,
                'final_amount' => $offer->final_amount,
            ])->values();

        return response()->json([
            'success' => true,
            'message' => 'Offers fetched successfully.',
            'data' => [
                'count' => $offers->count(),
                'offers' => $offers,
            ],
        ]);
    }
}
