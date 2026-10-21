<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileVisibilityController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->visibilityData($request->user()),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'profile_hide' => ['required', 'string', 'in:Yes,No'],
            'active' => ['prohibited'],
            'pre_active' => ['prohibited'],
            'hide_for_days' => ['prohibited'],
            'hidden_date' => ['prohibited'],
        ]);

        /** @var Member $member */
        $member = $request->user();
        $hide = $validated['profile_hide'] === 'Yes';

        // Repeated hide requests retain the original hiding date.
        if ($hide && (strtolower(trim((string) $member->profile_hide)) !== 'yes'
            || trim((string) $member->hidden_date) === '')) {
            $member->hidden_date = now()->format('Y-m-d H:i:s');
        } elseif (! $hide) {
            $member->hidden_date = '';
        }

        $member->profile_hide = $validated['profile_hide'];
        // Zero means hidden until explicitly shown. Keep legacy NOT NULL fields valid.
        $member->hide_for_days = '0';
        $member->save();

        return response()->json([
            'success' => true,
            'message' => $hide ? 'Profile hidden successfully.' : 'Profile hiding removed successfully.',
            'data' => $this->visibilityData($member->refresh()),
        ]);
    }

    private function visibilityData(Member $member): array
    {
        // Match the existing API discovery filters, including legacy blank values.
        $isHidden = ! in_array(strtolower(trim((string) $member->profile_hide)), ['', 'no'], true);

        return [
            'active' => $member->active,
            'pre_active' => $member->pre_active,
            'profile_hide' => $member->profile_hide,
            'hide_for_days' => $member->hide_for_days,
            'hidden_date' => $member->hidden_date,
            'is_active' => $member->is_active,
            'is_hidden' => $isHidden,
            'is_visible' => $member->is_active && ! $isHidden,
        ];
    }
}
