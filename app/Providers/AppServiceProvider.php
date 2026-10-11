<?php

namespace App\Providers;

use App\Models\MemberWalletPayment;
use App\Models\PersonalAccessToken;
use App\Models\ProfileViewed;
use App\Models\SentInterest;
use App\Models\ShortListed;
use App\Services\MemberActivityHistory;
use App\Website\Models\Shortlist;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        foreach ([
            ShortListed::class, SentInterest::class,
            ProfileViewed::class, MemberWalletPayment::class,
            Shortlist::class, \App\Website\Models\SentInterest::class,
            \App\Website\Models\ProfileViewed::class,
        ] as $model) {
            foreach (['created', 'updated', 'deleted'] as $event) {
                $model::$event(fn ($record) => app(MemberActivityHistory::class)->modelEvent($record, $event));
            }
        }
    }
}
