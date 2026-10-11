<?php

namespace App\Website\Support;

use App\Website\Models\SiteContentSetting;
use Illuminate\Support\Facades\Schema;

class SiteContentSettings
{
    public static function apply(array $site): array
    {
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
