<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Website\Models\SiteContentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SiteContentSettingsController extends Controller
{
    private function site(): array
    {
        abort_unless(auth('admin')->user()?->hasAnyRole(['super-admin', 'content-manager']), 403);

        $selected = Site::findOrFail(session('admin_site_id'));
        foreach (config('site.sites', []) as $key => $site) {
            if (($site['code'] ?? null) === $selected->code) {
                return array_merge($site, ['key' => $key]);
            }
        }
        abort(404, 'No website configuration exists for the selected site.');
    }

    public function edit()
    {
        $site = $this->site();
        $ready = Schema::connection('site')->hasTable('site_content_settings');

        return view('admin.settings.site-content', [
            'site' => $site, 'ready' => $ready, 'groups' => SiteContentSetting::fields(),
            'settings' => $ready ? SiteContentSetting::firstOrNew(['site_key' => $site['key']]) : new SiteContentSetting,
        ]);
    }

    public function update(Request $request)
    {
        $site = $this->site();
        abort_unless(Schema::connection('site')->hasTable('site_content_settings'), 409, 'Run the site content settings migration first.');
        // Preserve edits even when autofill or a browser fails to fire input events.
        $modes = $request->input('modes', []);
        $values = $request->input('values', []);
        if (is_array($modes) && is_array($values)) {
            foreach (array_merge(...array_values(SiteContentSetting::fields())) as $field) {
                if ($field === 'instagram_access_token' || ($modes[$field] ?? null) !== 'default' || ! array_key_exists($field, $values)) {
                    continue;
                }
                $default = data_get($site, SiteContentSetting::configPath($field), '');
                if (in_array($field, ['show_hero_stats', 'instagram_feed_enabled'])) {
                    $default = $default ? '1' : '0';
                }
                if ($field === 'instagram_api_base_url' && ! $default) {
                    $default = 'https://graph.instagram.com';
                }
                if (is_scalar($values[$field]) && (string) $values[$field] !== (string) $default) {
                    $modes[$field] = 'custom';
                } elseif ($values[$field] === null && (string) $default !== '') {
                    $modes[$field] = 'custom';
                }
            }
            $request->merge(['modes' => $modes]);
        }
        $rules = ['logo_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']];
        $attributes = [];
        foreach (array_merge(...array_values(SiteContentSetting::fields())) as $field) {
            $attributes['values.'.$field] = str_replace('_', ' ', $field);
            $attributes['modes.'.$field] = str_replace('_', ' ', $field).' source';
            $rules['modes.'.$field] = ['required', Rule::in($field === 'instagram_access_token' ? ['default', 'custom', 'keep'] : ['default', 'custom'])];
            $rule = match (true) {
                in_array($field, ['show_hero_stats', 'instagram_feed_enabled']) => ['boolean'],
                $field === 'instagram_cache_minutes' => ['integer', 'min:1', 'max:525600'],
                $field === 'instagram_feed_limit' => ['integer', 'min:1', 'max:100'],
                str_ends_with($field, '_color') => ['regex:/^#[0-9a-fA-F]{6}$/'],
                str_starts_with($field, 'social_') || in_array($field, ['android_app_url', 'ios_app_url']) => ['url:http,https', 'max:1000'],
                $field === 'instagram_api_base_url' => ['url:https', Rule::in(['https://graph.instagram.com', 'https://graph.facebook.com'])],
                in_array($field, ['meta_description', 'meta_keywords']) => ['string', 'max:2000'],
                $field === 'instagram_api_version' => ['regex:/^v\d+\.\d+$/', 'max:50'],
                in_array($field, ['footer_text', 'hero_subtitle', 'why_text', 'community_subtitle', 'instagram_access_token']) => ['string', 'max:20000'],
                in_array($field, ['logo', 'tagline', 'hero_background']) => ['string', 'max:500'],
                in_array($field, ['support_phone', 'google_analytics_id', 'google_tag_manager_id']) => ['string', 'max:50'],
                in_array($field, ['hero_cta_primary', 'hero_cta_secondary', 'instagram_user_id']) => ['string', 'max:100'],
                default => ['string', 'max:255'],
            };
            $rules['values.'.$field] = array_merge(['exclude_unless:modes.'.$field.',custom', 'nullable'], $rule);
            if (in_array($field, ['show_hero_stats', 'instagram_feed_enabled', 'instagram_cache_minutes', 'instagram_feed_limit']) || str_ends_with($field, '_color')) {
                $rules['values.'.$field][] = 'required_if:modes.'.$field.',custom';
            }
        }
        $data = $request->validate($rules, [], $attributes);
        $settings = SiteContentSetting::firstOrNew(['site_key' => $site['key']]);
        foreach (array_merge(...array_values(SiteContentSetting::fields())) as $field) {
            $mode = $data['modes'][$field];
            if ($mode === 'keep') {
                continue;
            }
            // A blank token means keep the existing encrypted token.
            if ($field === 'instagram_access_token' && $mode === 'custom' && empty($data['values'][$field])) {
                continue;
            }
            $settings->$field = $mode === 'default' ? null : ($data['values'][$field] ?? '');
        }
        if ($request->hasFile('logo_upload')) {
            $file = $request->file('logo_upload');
            $directory = 'uploads/site-logos/'.Str::slug($site['key']);
            $filename = Str::uuid().'.'.$file->extension();
            $file->move(public_path($directory), $filename);
            $settings->logo = $directory.'/'.$filename;
        }
        $settings->save();
        Cache::forget('instagram_feed_'.md5($site['key']));

        return redirect()->route('admin.settings.content.edit')->with('success', 'Site content settings saved.');
    }
}
