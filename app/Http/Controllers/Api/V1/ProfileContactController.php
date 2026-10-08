<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Services\ProfileContactUnlock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class ProfileContactController extends Controller
{
    /**
     * Unlock a member's contact details using the current profile-range rate.
     */
    public function unlock(Request $request, int $profileId): JsonResponse
    {
        /** @var Member|null $member */
        $member = $request->user();

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ((int) $member->id === $profileId) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot unlock your own contact details.',
            ], 422);
        }

        $profile = Member::query()
            ->whereKey($profileId)
            ->whereRaw("LOWER(TRIM(active)) = 'yes'")
            ->where(function ($query) {
                $query->whereNull('profile_hide')
                    ->orWhere('profile_hide', '')
                    ->orWhereRaw("LOWER(TRIM(profile_hide)) = 'no'");
            })
            ->first();

        if (! $profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found.',
            ], 404);
        }

        try {
            $result = app(ProfileContactUnlock::class)->unlock((int) $member->id, (int) $profile->id, 'application');
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Unable to unlock contact details.',
            ], 500);
        }

        if ($result['inactive_membership'] ?? false) {
            return response()->json(['success' => false, 'message' => 'Active membership required to unlock contact details.'], 403);
        }

        if ($result['insufficient_balance'] ?? false) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance.',
                'data' => [
                    'required_coins' => $result['required_coins'],
                    'wallet_balance' => $result['wallet_balance'],
                    'profile_view_number' => $result['profile_view_number'],
                ],
            ], 402);
        }

        return response()->json([
            'success' => true,
            'message' => ($result['already_unlocked'] ?? false)
                ? 'Contact details were already unlocked.'
                : 'Contact details unlocked successfully.',
            'data' => [
                'profile' => [
                    'id' => $profile->id,
                    'profile_id' => $profile->profile_id,
                    'full_name' => $profile->full_name,
                ],
                'contact_details' => [
                    'email' => $profile->email,
                    'mobile_number' => $profile->mobile_number,
                    'alternate_number' => $profile->alternate_number,
                    'whatsapp_number' => $profile->whatsapp_number,
                ],
                'already_unlocked' => $result['already_unlocked'],
                'coins_deducted' => $result['coins_deducted'],
                'wallet_balance' => $result['wallet_balance'],
                'profile_view_number' => $result['profile_view_number'],
                'price_range' => $result['price_range'] ?? null,
            ],
        ]);
    }
}
