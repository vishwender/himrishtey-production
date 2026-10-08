<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProfileContactUnlock
{
    public function price(int $memberId, string $connection = 'site'): array
    {
        $db = DB::connection($connection);
        $number = $db->table('viewed_contacts')->where('member_id', $memberId)->distinct()->count('profile_id') + 1;
        $ranges = $db->table('member_profile_range')->where('member_id', $memberId)->get();
        $column = 'price';
        if ($ranges->isEmpty()) {
            $ranges = $db->table('profile_ranges')->get();
            $column = 'rate';
        }

        $range = $ranges->sortBy(fn ($range) => (int) $range->range_from)->first(
            fn ($range) => $number >= (int) $range->range_from && $number <= (int) $range->range_to
        );
        if (! $range || ! is_numeric($range->{$column}) || (float) $range->{$column} < 0) {
            throw new RuntimeException("No valid profile-view price is configured for contact unlock {$number}.");
        }

        return [
            'price' => (float) $range->{$column},
            'profile_view_number' => $number,
            'price_range' => ['from' => (int) $range->range_from, 'to' => (int) $range->range_to],
        ];
    }

    public function unlock(int $memberId, int $profileId, string $connection = 'site'): array
    {
        $db = DB::connection($connection);

        return $db->transaction(function () use ($db, $memberId, $profileId, $connection) {
            // All clients lock the same member row before checking or charging.
            $member = $db->table('members')->where('id', $memberId)->lockForUpdate()->first();
            if (! $member || strtolower(trim((string) $member->active)) !== 'yes') {
                return ['inactive_membership' => true];
            }

            $wallet = $db->table('member_wallet')->where('member_id', $memberId)->orderByDesc('id')->lockForUpdate()->first();
            $balance = is_numeric($wallet?->wallet_balance) ? (float) $wallet->wallet_balance : 0.0;
            if ($db->table('viewed_contacts')->where('member_id', $memberId)->where('profile_id', $profileId)->exists()) {
                return ['already_unlocked' => true, 'coins_deducted' => 0.0, 'wallet_balance' => $balance, 'profile_view_number' => null];
            }

            $pricing = $this->price($memberId, $connection);
            $price = $pricing['price'];
            if ($balance < $price) {
                return ['insufficient_balance' => true, 'required_coins' => $price, 'wallet_balance' => $balance] + $pricing;
            }

            $newBalance = round($balance - $price, 2);
            $db->table('member_wallet')->insert([
                'member_id' => $memberId, 'wallet_balance' => $newBalance,
                'amount_deducted' => $price, 'amount_added' => 0,
                'created_at' => now(),
            ]);
            $db->table('viewed_contacts')->insert([
                'member_id' => $memberId, 'profile_id' => $profileId, 'viewed_date' => now(),
            ]);

            return ['already_unlocked' => false, 'coins_deducted' => $price, 'wallet_balance' => $newBalance] + $pricing;
        }, 3);
    }
}
