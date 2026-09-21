<?php

namespace App\Website\Providers;

use App\Website\Auth\MemberTokenUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class WebsiteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::provider('member_tokens', function ($app, array $config) {
            return new MemberTokenUserProvider($app['hash'], $config['model']);
        });

        View::composer(['pages.*', 'layouts.*', 'dashboard.*', 'auth.*', 'emails.*', 'blog.*'], function ($view): void {
            $site = config('site.current', []);
            $siteName = $site['display_name'] ?? $site['name'] ?? config('app.name', 'HimRishtey');

            $view->with('siteName', $siteName);
            $view->with('siteMetaTitle', ($site['meta_title'] ?? null) ?: $siteName.' — Find someone who feels like home');
            $view->with('siteMetaDescription', ($site['meta_description'] ?? null) ?: 'Find genuine matrimonial profiles with '.$siteName.'. Discover brides, grooms, and trusted matchmaking services for your perfect life partner.');
            $view->with('siteTagline', $site['tagline'] ?? 'Connecting hearts across Himachal Pradesh & beyond.');
            $view->with('siteFooterText', $site['footer_text'] ?? 'Trusted matrimony services for families.');
            $view->with('siteLogo', $site['logo'] ?? 'assets/images/himrishtey-logo.png');
            $view->with('siteHeroBadge', $site['hero_badge'] ?? "Himachal Pradesh's Most Trusted Matrimony");
            $view->with('siteHeroTitle', $site['hero_title'] ?? 'We Connect Hearts, Not Just Relationships');
            $view->with('siteHeroTitleSecondary', $site['hero_title_secondary'] ?? 'Find your forever from the hills.');
            $view->with('siteHeroSubtitle', $site['hero_subtitle'] ?? 'Thousands of happy families found their perfect match through HimRishtey.');
            $view->with('siteHeroBackground', $site['hero_background'] ?? null);
            $view->with('siteHeroCtaPrimary', $site['hero_cta_primary'] ?? 'Create Your Profile');
            $view->with('siteHeroCtaSecondary', $site['hero_cta_secondary'] ?? 'Browse Profiles');
            $view->with('siteShowHeroStats', $site['show_hero_stats'] ?? true);
            $view->with('siteWhyTitle', $site['why_title'] ?? 'Everything You Need on One Platform');
            $view->with('siteWhyText', $site['why_text'] ?? 'A trusted platform for meaningful connections and family-first matchmaking.');
            $view->with('siteCommunityTitle', $site['community_title'] ?? 'Matches for Every Family');
            $view->with('siteCommunitySubtitle', $site['community_subtitle'] ?? 'Explore thoughtful profiles built around culture, values, and compatibility.');
            $view->with('sitePrimaryColor', $site['primary_color'] ?? '#b92c3d');
            $view->with('siteSecondaryColor', $site['secondary_color'] ?? '#2f2d5c');
            $view->with('siteAccentColor', $site['accent_color'] ?? '#f4c86c');
            $view->with('siteKey', $site['key'] ?? 'himrishtey.com');
            $view->with('siteSupportEmail', $site['support_email'] ?? null);
            $view->with('siteSupportPhone', $site['support_phone'] ?? null);
            $view->with('siteSupportAddress', $site['support_address'] ?? null);
            $view->with('siteSocial', $site['social'] ?? []);
            $view->with('siteAndroidAppUrl', $site['android_app_url'] ?? null);
            $view->with('siteAndroidAppQr', $site['android_app_qr'] ?? null);
            $view->with('siteIosAppQr', $site['ios_app_qr'] ?? null);
            $view->with('siteIosAppUrl', $site['ios_app_url'] ?? null);
            $view->with('siteCurrent', $site);
        });

        View::composer('layouts.dashboard', function ($view): void {
            $member = Auth::guard('member')->user();
            $member?->loadMissing('membershipPlan');

            $view->with('dashboardMember', $member);
            $view->with('dashboardPlan', $member?->membershipPlan);
        });
    }
}
