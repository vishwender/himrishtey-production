<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    public function index(): JsonResponse
    {
        $offers = Offer::query()
            ->whereRaw('LOWER(TRIM(status)) = ?', ['active'])
            ->orderByDesc('id')
            ->get(['id', 'title', 'description', 'image', 'offer_date', 'offer_time', 'status'])
            ->map(fn (Offer $offer): array => [
                'id' => (int) $offer->id,
                'title' => $offer->title,
                'description' => $offer->description,
                'image' => $offer->image,
                'image_url' => filled($offer->image) ? asset('images/offers/'.$offer->image) : null,
                'offer_date' => $offer->offer_date,
                'offer_time' => $offer->offer_time,
                'status' => $offer->status,
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
