<?php

namespace App\Services\Api\V1;

use App\Models\SentInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class InterestPagination
{
    /** Fetch one forward-only page, scoped to the authenticated member and application. */
    public function page(Request $request, bool $received = false): array
    {
        $input = $request->validate([
            'per_page' => ['sometimes', 'integer', 'between:1,100'],
            'cursor' => ['sometimes', 'string', 'max:16384'],
        ]);
        $member = $request->user();
        $context = hash('sha256', json_encode([
            'version' => 1,
            'database' => (new SentInterest)->getConnection()->getDatabaseName(),
            'application' => $request->header('X-App-Code'),
            'member_id' => $member->id,
            'direction' => $received ? 'received' : 'sent',
        ], JSON_THROW_ON_ERROR));
        $expires = now()->timestamp + 3600;
        $lastId = null;
        if (isset($input['cursor'])) {
            try {
                $state = json_decode(Crypt::decryptString($input['cursor']), true, flags: JSON_THROW_ON_ERROR);
                if (($state['context'] ?? null) !== $context
                    || ! is_int($state['expires'] ?? null) || $state['expires'] <= now()->timestamp
                    || ! is_int($state['last_id'] ?? null) || $state['last_id'] < 1) {
                    throw new \InvalidArgumentException;
                }
                $expires = $state['expires'];
                $lastId = $state['last_id'];
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages(['cursor' => 'Invalid or expired cursor. Restart this list without a cursor.']);
            }
        }

        $limit = (int) ($input['per_page'] ?? 20);
        $relation = $received ? 'sender' : 'recipient';
        $items = SentInterest::query()
            ->select(['id', 'member_id', 'profile_id', 'status', 'created_at'])
            ->with([$relation.':id,profile_id,full_name,gender,photo,birth_date_time,city_living_in,state_living_in'])
            ->where($received ? 'profile_id' : 'member_id', $member->id)
            ->when($lastId !== null, fn ($query) => $query->where('id', '<', $lastId))
            ->orderByDesc('id')->limit($limit + 1)->get();
        $hasMore = $items->count() > $limit;
        if ($hasMore) {
            $items->pop();
        }
        $nextCursor = $hasMore ? Crypt::encryptString(json_encode([
            'context' => $context, 'expires' => $expires, 'last_id' => (int) $items->last()->id,
        ], JSON_THROW_ON_ERROR)) : null;

        return [$items, $nextCursor];
    }
}
