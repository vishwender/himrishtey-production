<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Services\ProfileMatchingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DiscoverProfilesController extends Controller
{
    public function __invoke(Request $request, ProfileMatchingService $matching): JsonResponse
    {
        $input = $request->validate([
            'section' => ['sometimes', Rule::in(['matches', 'recently_joined', 'verified', 'shortlisted', 'viewed_you'])],
            'per_page' => ['sometimes', 'integer', 'between:1,100'],
            'cursor' => ['sometimes', 'string', 'max:16384'],
        ]);
        /** @var Member $member */
        $member = $request->user();
        $section = $input['section'] ?? 'matches';
        if (! in_array(strtolower(trim((string) $member->gender)), ['male', 'female'], true)) {
            throw ValidationException::withMessages(['gender' => 'Please set your profile gender before discovering profiles.']);
        }

        $connection = $member->getConnection();
        $preferences = collect($member->getAttributes())->filter(fn ($value, $key) => str_starts_with($key, 'partner_') || str_starts_with($key, 'is_partner_') || $key === 'gender')
            ->sortKeys()->all();
        $context = hash('sha256', json_encode([
            'v' => 1, 'database' => $connection->getDatabaseName(),
            'application' => $request->header('X-App-Code'), 'member' => $member->id,
            'section' => $section, 'preferences' => $preferences,
        ], JSON_THROW_ON_ERROR));
        $cursor = null;
        if (isset($input['cursor'])) {
            try {
                $state = json_decode(Crypt::decryptString($input['cursor']), true, flags: JSON_THROW_ON_ERROR);
                if (($state['context'] ?? null) !== $context || ($state['expires'] ?? 0) < now()->timestamp
                    || ! is_int($state['max_id'] ?? null) || ! is_int($state['max_event_id'] ?? null)
                    || ! is_string($state['as_of'] ?? null) || ! is_string($state['position'] ?? null)) {
                    throw new \InvalidArgumentException;
                }
                $cursor = Cursor::fromEncoded($state['position']);
                if (! $cursor || ! $cursor->pointsToNextItems()) {
                    throw new \InvalidArgumentException;
                }
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages(['cursor' => 'Invalid or expired cursor. Restart this list without a cursor.']);
            }
        } else {
            $state = [
                'context' => $context, 'expires' => now()->timestamp + 3600,
                'as_of' => Carbon::today()->format('Y-m-d'),
                'max_id' => (int) Member::query()->max('id'), 'max_event_id' => 0,
            ];
            if (in_array($section, ['shortlisted', 'viewed_you'], true)) {
                $state['max_event_id'] = (int) $connection->table($section === 'shortlisted' ? 'short_listed' : 'profile_viewed')->max('id');
            }
        }

        $query = $section === 'matches'
            ? $matching->rankedQuery($member, Carbon::parse($state['as_of']))
            : $matching->eligibleQuery($member)->select(ProfileMatchingService::CARD_FIELDS);
        $query->where('id', '<=', $state['max_id']);
        $order = ['match_score', 'promoted_rank', 'id'];
        if ($section === 'verified') {
            $query->whereRaw("LOWER(TRIM(member_type)) = 'verified'")
                ->selectRaw("CASE WHEN LOWER(TRIM(promoted)) = 'yes' THEN 1 ELSE 0 END AS promoted_rank");
            $order = ['promoted_rank', 'id'];
        } elseif ($section === 'recently_joined') {
            $query->selectRaw("COALESCE(registration_date, '') AS joined_rank");
            $order = ['joined_rank', 'id'];
        } elseif (in_array($section, ['shortlisted', 'viewed_you'], true)) {
            $shortlisted = $section === 'shortlisted';
            $table = $shortlisted ? 'short_listed' : 'profile_viewed';
            $profileColumn = $shortlisted ? 'profile_id' : 'member_id';
            $ownerColumn = $shortlisted ? 'member_id' : 'viewed_profile_id';
            $events = $connection->table($table)
                ->select($profileColumn)->selectRaw('MAX(id) AS latest_event_id')
                ->where($ownerColumn, $member->id)->where('id', '<=', $state['max_event_id'])
                ->groupBy($profileColumn);
            $query->joinSub($events, 'discovery_events', fn ($join) => $join->on('members.id', '=', 'discovery_events.'.$profileColumn))
                ->select(array_map(fn ($field) => 'members.'.$field, ProfileMatchingService::CARD_FIELDS))
                ->addSelect('discovery_events.latest_event_id');
            $order = ['latest_event_id', 'id'];
        }

        // Wrap computed columns so cursor predicates reference real derived columns.
        $query = Member::query()->fromSub($query->reorder()->toBase(), 'discovered_profiles');
        foreach ($order as $column) {
            $query->orderByDesc($column);
        }
        if ($cursor) {
            try {
                foreach ($order as $column) {
                    if (! is_scalar($cursor->parameter($column))) {
                        throw new \InvalidArgumentException;
                    }
                }
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages(['cursor' => 'Invalid cursor position.']);
            }
        }
        $page = $query->cursorPaginate($input['per_page'] ?? 20, ['*'], 'cursor', $cursor);
        $next = $page->nextCursor();
        $state['position'] = $next?->encode();
        $profiles = collect($page->items());
        $shortlistedIds = $profiles->isEmpty() ? collect() : $connection->table('short_listed')
            ->where('member_id', $member->id)
            ->whereIn('profile_id', $profiles->pluck('id'))
            ->distinct()->pluck('profile_id')->flip();
        $latestInterestIds = $connection->table('sent_interests')
            ->selectRaw('MAX(id)')->where('member_id', $member->id)
            ->whereIn('profile_id', $profiles->pluck('id'))->groupBy('profile_id');
        $interests = $profiles->isEmpty() ? collect() : $connection->table('sent_interests')
            ->whereIn('id', $latestInterestIds)->get(['id', 'profile_id', 'status'])->keyBy('profile_id');

        return response()->json([
            'data' => [
                'profiles' => $profiles->map(fn ($profile) => array_merge(
                    $matching->card($profile, $section === 'matches'),
                    [
                        'is_shortlisted' => $shortlistedIds->has($profile->id),
                        'interest_status' => isset($interests[$profile->id]) ? (string) $interests[$profile->id]->status : null,
                        'interest_id' => isset($interests[$profile->id]) ? (int) $interests[$profile->id]->id : null,
                    ],
                ))->values(),
                'next_cursor' => $next ? Crypt::encryptString(json_encode($state, JSON_THROW_ON_ERROR)) : null,
            ],
        ]);
    }
}
