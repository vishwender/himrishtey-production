<?php

namespace App\Website\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\DB;

class MemberTokenUserProvider extends EloquentUserProvider
{
    public function __construct(Hasher $hasher, string $model)
    {
        parent::__construct($hasher, $model);
    }

    public function retrieveByToken($identifier, #[\SensitiveParameter] $token): ?UserContract
    {
        $user = $this->createModel()->newQuery()
            ->where($this->createModel()->getAuthIdentifierName(), $identifier)
            ->first();

        if (! $user) {
            return null;
        }

        $hashedToken = DB::connection('site')->table('member_remember_tokens')
            ->where('member_id', $user->getAuthIdentifier())
            ->value('token');

        return $hashedToken && $this->hasher->check($token, $hashedToken) ? $user : null;
    }

    public function updateRememberToken(UserContract $user, #[\SensitiveParameter] $token): void
    {
        DB::connection('site')->table('member_remember_tokens')->updateOrInsert(
            ['member_id' => $user->getAuthIdentifier()],
            ['token' => $this->hasher->make($token), 'updated_at' => now()]
        );

        // The legacy members table cannot accept another column. Keeping the
        // value on this model lets SessionGuard build Laravel's recaller cookie.
        $user->setRememberToken($token);
    }
}
