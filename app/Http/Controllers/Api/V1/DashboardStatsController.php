<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Website\Models\ProfileViewed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardStatsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $member = $request->user();
        $db = $member->getConnection();
        // Count people, excluding self, hidden, inactive, and deleted members.
        $eligible = $db->table('members')
            ->where('id', '!=', $member->id)
            ->whereRaw('LOWER(TRIM(active)) = ?', ['yes'])
            ->where(fn ($query) => $query->whereNull('profile_hide')
                ->orWhereRaw('LOWER(TRIM(profile_hide)) != ?', ['yes']));

        $likes = $db->table('profile_like')->select('user_id')
            ->where('like_profile_id', $member->id)->where('status', 1);
        $interests = $db->table('sent_interests')->select('member_id')
            ->where('profile_id', $member->id);
        // member_id is the viewer; profile_id owns the unlocked contact details.
        $contacts = $db->table('viewed_contacts')->select('member_id')
            ->where('profile_id', $member->id);

        return response()->json([
            'success' => true,
            'data' => [
                'profile_views' => ProfileViewed::viewersFor((int) $member->id)->count(),
                'likes_received' => (clone $eligible)->whereIn('id', $likes)->count(),
                'interests_received' => (clone $eligible)->whereIn('id', $interests)->count(),
                'contact_views' => (clone $eligible)->whereIn('id', $contacts)->count(),
            ],
        ]);
    }
}
