<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContentSetting extends Model
{
    protected $connection = 'site';

    protected $guarded = ['id'];

    protected $hidden = ['instagram_access_token'];

    protected function casts(): array
    {
        return [
            'instagram_access_token' => 'encrypted',
            'show_hero_stats' => 'boolean',
            'instagram_feed_enabled' => 'boolean',
            'instagram_cache_minutes' => 'integer',
            'instagram_feed_limit' => 'integer',
        ];
    }

    public static function fields(): array
    {
        return [
            'Branding' => ['logo', 'tagline', 'footer_text', 'primary_color', 'secondary_color', 'accent_color'],
            'Home page' => ['hero_badge', 'hero_title', 'hero_title_secondary', 'hero_subtitle', 'hero_background', 'hero_cta_primary', 'hero_cta_secondary', 'show_hero_stats', 'why_title', 'why_text', 'community_title', 'community_subtitle'],
            'About page' => ['about_intro_title', 'about_intro_title_secondary'],
            'Contact and apps' => ['support_phone', 'android_app_url', 'ios_app_url'],
            'SEO' => ['meta_title', 'meta_description', 'meta_keywords', 'google_site_verification'],
            'Analytics' => ['google_analytics_id', 'google_tag_manager_id'],
            'Social links' => ['social_facebook', 'social_x', 'social_instagram', 'social_youtube', 'social_pinterest'],
            'Instagram feed' => ['instagram_feed_enabled', 'instagram_api_base_url', 'instagram_api_version', 'instagram_cache_minutes', 'instagram_feed_limit', 'instagram_user_id', 'instagram_access_token'],
        ];
    }

    public static function configPath(string $field): string
    {
        return match ($field) {
            'instagram_feed_enabled' => 'instagram.enabled',
            'instagram_feed_limit' => 'instagram.feed_limit',
            default => str_starts_with($field, 'social_') ? 'social.'.substr($field, 7)
                : (str_starts_with($field, 'instagram_') ? 'instagram.'.substr($field, 10) : $field),
        };
    }
}
