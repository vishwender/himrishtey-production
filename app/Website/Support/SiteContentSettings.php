<?php

namespace App\Website\Support;

use App\Website\Models\SiteContentSetting;
use Illuminate\Support\Facades\Schema;

class SiteContentSettings
{
    public static function withDefaults(array $site): array
    {
        $site['instagram'] = array_replace([
            'enabled' => config('instagram.enabled', true),
            'api_base_url' => config('instagram.base_url', 'https://graph.instagram.com'),
            'api_version' => config('instagram.api_version'),
            'cache_minutes' => config('instagram.cache_minutes', 60),
            'feed_limit' => config('instagram.limit', 6),
        ], $site['instagram'] ?? []);

        return $site;
    }

    public static function apply(array $site): array
    {
        $site = static::withDefaults($site);

        // Allow sites to continue serving while the new migration is rolled out.
        if (! Schema::connection('site')->hasTable('site_content_settings')) {
            return $site;
        }

        $settings = SiteContentSetting::where('site_key', $site['key'])->first();
        if ($settings) {
            foreach (array_merge(...array_values(SiteContentSetting::fields())) as $field) {
                if ($settings->$field !== null) {
                    data_set($site, SiteContentSetting::configPath($field), $settings->$field);
                }
            }
        }

        return $site;
    }
}
