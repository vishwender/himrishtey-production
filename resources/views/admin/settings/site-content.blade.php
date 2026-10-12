@extends('admin.layout')
@section('title', 'Site Content Settings')
@section('page-title', 'Site Content Settings')
@push('styles')
<style>
    #site-content-form { --settings-header-height: 90px; }
    #site-content-form .settings-section-menu { position: static; z-index: 1; }
    #site-content-form .settings-section { scroll-margin-top: calc(var(--settings-header-height) + 20px); }
    @media (min-width: 992px) {
        #site-content-form .settings-section-menu {
            position: sticky;
            top: calc(var(--settings-header-height) + 20px);
            max-height: calc(100dvh - var(--settings-header-height) - 40px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }
    }
</style>
@endpush
@section('content')
@php
    $labels = [
        'meta_title' => 'Meta title', 'meta_description' => 'Meta description', 'meta_keywords' => 'Meta keywords',
        'logo' => 'Logo image', 'hero_badge' => 'Hero badge text', 'hero_title' => 'Main headline',
        'hero_title_secondary' => 'Secondary headline', 'hero_subtitle' => 'Hero description',
        'hero_background' => 'Hero background image', 'hero_cta_primary' => 'Primary button label',
        'hero_cta_secondary' => 'Secondary button label', 'show_hero_stats' => 'Show homepage statistics',
        'why_title' => 'Why choose us heading', 'why_text' => 'Why choose us description',
        'community_title' => 'Community section heading', 'community_subtitle' => 'Community description',
        'about_intro_title' => 'About introduction heading', 'about_intro_title_secondary' => 'About secondary heading',
        'android_app_url' => 'Google Play app link', 'ios_app_url' => 'App Store link',
        'google_analytics_id' => 'Google Analytics measurement ID', 'google_tag_manager_id' => 'Google Tag Manager container ID',
        'instagram_feed_enabled' => 'Show Instagram feed', 'instagram_api_base_url' => 'Instagram API provider',
        'instagram_api_version' => 'API version', 'instagram_cache_minutes' => 'Refresh interval (minutes)',
        'instagram_feed_limit' => 'Number of posts', 'instagram_user_id' => 'Instagram account ID',
        'instagram_access_token' => 'Instagram access token',
    ];
    $help = [
        'meta_title' => 'The website title used in browser tabs and search results. Default uses this site’s configured meta title.',
        'meta_keywords' => 'Comma-separated keywords for this website. Default uses this site’s configured keywords. Leave a custom value blank to remove the keywords tag.',
        'meta_description' => 'A short description of your website for search results. Default uses this site’s configured meta description.',
        'logo' => 'Enter a public asset path or HTTPS image URL, for example assets/images/logo.png.',
        'hero_background' => 'Enter a public asset path or HTTPS image URL for the homepage banner.',
        'hero_cta_primary' => 'Text displayed on the profile registration button.',
        'hero_cta_secondary' => 'Text displayed on the browse profiles button.',
        'google_analytics_id' => 'For example G-XXXXXXXXXX.',
        'google_tag_manager_id' => 'For example GTM-XXXXXXX.',
        'instagram_api_version' => 'Use the version supported by your account, for example v23.0. Leave blank to use an unversioned endpoint.',
        'instagram_user_id' => 'Leave blank to use the authenticated account (me).',
        'instagram_cache_minutes' => 'Choose between 1 and 525600 minutes.',
        'instagram_feed_limit' => 'Choose between 1 and 100 posts.',
    ];
@endphp
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div><h1 class="h3 mb-2">Site Content Settings</h1><p class="text-muted mb-0">Manage your website branding, page content, social links, and integrations.</p></div>
    <span class="badge bg-primary p-3">{{ $site['display_name'] ?? $site['name'] }} · {{ $site['key'] }}</span>
</div>
@if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())
<div class="alert alert-danger" role="alert"><strong>Please correct the highlighted fields.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
@if(!$ready)
<div class="alert alert-warning">Settings are not available yet. Run the site content settings migration for this site.</div>
@else
<form method="POST" action="{{ route('admin.settings.content.update') }}" id="site-content-form" enctype="multipart/form-data">
@csrf
@method('PUT')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm settings-section-menu">
            <div class="card-body"><h2 class="h6">Settings sections</h2><nav class="nav flex-column" aria-label="Settings sections">
                <a class="nav-link px-0" href="#settings-logo">Website logo</a>
                @foreach($groups as $group => $fields)<a class="nav-link px-0" href="#settings-{{ $loop->index }}">{{ $group }}</a>@endforeach
            </nav><hr><p class="small text-muted">Existing website values are shown below. Editing a value selects Custom automatically. Choose Default to restore the configured value.</p><button class="btn btn-primary w-100" type="submit">Save Settings</button></div>
        </div>
    </div>
    <div class="col-lg-9">
    @php
        $currentLogo = $settings->logo ?? ($site['logo'] ?? '');
    @endphp
    <section class="card border-0 shadow-sm mb-4 settings-section" id="settings-logo" aria-labelledby="logo-upload-heading">
        <div class="card-header bg-white py-3"><h2 class="h5 mb-0" id="logo-upload-heading">Website logo</h2></div>
        <div class="card-body p-4"><div class="row g-4 align-items-center">
            <div class="col-md-4">
                <p class="form-label">Current logo</p>
                @if($currentLogo !== '')
                <div class="border rounded p-3 text-center bg-light"><img src="{{ asset($currentLogo) }}" alt="Current {{ $site['display_name'] ?? $site['name'] }} logo" style="max-width: 100%; max-height: 150px; object-fit: contain;"></div>
                @else<p class="text-muted">No logo is currently set.</p>@endif
            </div>
            <div class="col-md-8">
                <label for="logo-upload" class="form-label">Upload a new logo</label>
                <input type="file" id="logo-upload" name="logo_upload" accept="image/jpeg,image/png,image/webp" class="form-control @error('logo_upload') is-invalid @enderror" aria-describedby="logo-upload-help">
                @error('logo_upload')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text" id="logo-upload-help">JPG, PNG, or WebP, up to 5 MB. Uploading replaces the logo when you save settings. PNG or WebP works well for transparent logos.</div>
                <div id="logo-upload-preview" class="mt-3" hidden><p class="small text-muted mb-2">New logo preview</p><img id="logo-upload-preview-image" alt="New logo preview" style="max-width: 100%; max-height: 150px; object-fit: contain;"></div>
            </div>
        </div></div>
    </section>
    @foreach($groups as $group => $fields)
        <section class="card border-0 shadow-sm mb-4 settings-section" id="settings-{{ $loop->index }}" aria-labelledby="heading-{{ $loop->index }}">
            <div class="card-header bg-white py-3"><h2 class="h5 mb-0" id="heading-{{ $loop->index }}">{{ $group }}</h2></div>
            <div class="card-body p-4"><div class="row g-4">
            @foreach($fields as $field)
            @php
                $secret = $field === 'instagram_access_token';
                $boolean = in_array($field, ['show_hero_stats', 'instagram_feed_enabled']);
                $number = in_array($field, ['instagram_cache_minutes', 'instagram_feed_limit']);
                $color = str_ends_with($field, '_color');
                $url = str_starts_with($field, 'social_') || in_array($field, ['android_app_url', 'ios_app_url']);
                $multiline = in_array($field, ['meta_description', 'meta_keywords', 'footer_text', 'hero_subtitle', 'why_text', 'community_subtitle']);
                $label = $labels[$field] ?? (str_starts_with($field, 'social_') ? ucwords(substr($field, 7)).' profile URL' : ucwords(str_replace('_', ' ', $field)));
                $value = $secret ? '' : old('values.'.$field, $settings->$field ?? data_get($site, \App\Website\Models\SiteContentSetting::configPath($field), ''));
                $mode = old('modes.'.$field, $secret ? 'keep' : ($settings->$field === null ? 'default' : 'custom'));
                $error = $errors->has('values.'.$field) || $errors->has('modes.'.$field);
                $max = in_array($field, ['logo', 'tagline', 'hero_background']) ? 500 : ($url ? 1000 : (in_array($field, ['support_phone', 'google_analytics_id', 'google_tag_manager_id', 'instagram_api_version']) ? 50 : (in_array($field, ['hero_cta_primary', 'hero_cta_secondary', 'instagram_user_id']) ? 100 : 255)));
            @endphp
            <div class="{{ $multiline || $secret ? 'col-12' : 'col-md-6' }}" data-setting-field>
                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <label for="value-{{ $field }}" class="form-label mb-0">{{ $label }}</label>
                    <select name="modes[{{ $field }}]" class="form-select form-select-sm w-auto" data-value-mode data-default-value="{{ $secret ? '' : data_get($site, \App\Website\Models\SiteContentSetting::configPath($field), '') }}" aria-label="{{ $label }} source">
                        @if($secret)<option value="keep" @selected($mode === 'keep')>Keep token</option>@endif
                        <option value="default" @selected($mode === 'default')>Default</option>
                        <option value="custom" @selected($mode === 'custom')>Custom</option>
                    </select>
                </div>
                @if($boolean)
                    <select id="value-{{ $field }}" name="values[{{ $field }}]" class="form-select {{ $error ? 'is-invalid' : '' }}" data-setting-input aria-describedby="help-{{ $field }}"><option value="1" @selected((bool)$value)>Enabled</option><option value="0" @selected(!(bool)$value)>Disabled</option></select>
                @elseif($field === 'instagram_api_base_url')
                    <select id="value-{{ $field }}" name="values[{{ $field }}]" class="form-select {{ $error ? 'is-invalid' : '' }}" data-setting-input><option value="https://graph.instagram.com" @selected($value !== 'https://graph.facebook.com')>Instagram Graph API</option><option value="https://graph.facebook.com" @selected($value === 'https://graph.facebook.com')>Facebook Graph API</option></select>
                @elseif($multiline)
                    <textarea id="value-{{ $field }}" name="values[{{ $field }}]" class="form-control {{ $error ? 'is-invalid' : '' }}" rows="3" maxlength="{{ in_array($field, ['meta_description', 'meta_keywords']) ? 2000 : 20000 }}" data-setting-input aria-describedby="help-{{ $field }}">{{ $value }}</textarea>
                @else
                    @if($color)<div class="input-group"><span class="input-group-text"><input type="color" class="form-control form-control-color border-0 p-0" value="{{ preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : '#000000' }}" data-color-picker aria-label="Pick {{ $label }}"></span>@endif
                    <input id="value-{{ $field }}" name="values[{{ $field }}]" type="{{ $secret ? 'password' : ($number ? 'number' : ($url ? 'url' : ($field === 'support_phone' ? 'tel' : 'text'))) }}" value="{{ $value }}" class="form-control {{ $error ? 'is-invalid' : '' }}" data-setting-input aria-describedby="help-{{ $field }}" @if($secret) autocomplete="new-password" maxlength="20000" @elseif($number) min="1" max="{{ $field === 'instagram_feed_limit' ? 100 : 525600 }}" step="1" @else maxlength="{{ $color ? 7 : $max }}" @endif @if($color) placeholder="#b92c3d" @elseif($url) placeholder="https://" @endif>
                    @if($color)</div>@endif
                @endif
                @error('values.'.$field)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('modes.'.$field)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <div id="help-{{ $field }}" class="form-text">
                    @if($secret)Saved tokens are hidden and encrypted. Enter a new token to replace it; blank keeps the existing token. Default uses the configured token.
                    @elseif(isset($help[$field])){{ $help[$field] }}
                    @elseif($color)Enter a six-digit hex color or use the color picker.
                    @elseif($url)Use a complete HTTPS or HTTP link. Blank custom text removes this link.
                    @elseif(!$boolean && !$number)Blank custom text clears this value.
                    @else Choose Custom to save your selection.
                    @endif
                </div>
            </div>
            @endforeach
            </div></div>
        </section>
    @endforeach
    <div class="d-flex justify-content-end mb-4"><button class="btn btn-primary px-4" type="submit">Save Settings</button></div>
    </div>
</div>
</form>
<script>
const settingsForm = document.getElementById('site-content-form');
const adminHeader = document.querySelector('.topbar');
function updateSettingsHeaderHeight() {
    const height = adminHeader && getComputedStyle(adminHeader).position === 'sticky'
        ? Math.ceil(adminHeader.getBoundingClientRect().height) : 0;
    settingsForm.style.setProperty('--settings-header-height', height + 'px');
}
updateSettingsHeaderHeight();
window.addEventListener('resize', updateSettingsHeaderHeight);
if (adminHeader && typeof ResizeObserver !== 'undefined') {
    new ResizeObserver(updateSettingsHeaderHeight).observe(adminHeader);
}
const logoUpload = document.getElementById('logo-upload');
let logoPreviewUrl;
logoUpload.addEventListener('change', function () {
    if (logoPreviewUrl) URL.revokeObjectURL(logoPreviewUrl);
    const file = this.files[0];
    const preview = document.getElementById('logo-upload-preview');
    preview.hidden = !file || !['image/jpeg', 'image/png', 'image/webp'].includes(file.type);
    if (!preview.hidden) {
        logoPreviewUrl = URL.createObjectURL(file);
        document.getElementById('logo-upload-preview-image').src = logoPreviewUrl;
        document.querySelector('[name="modes[logo]"]').value = 'custom';
    }
});
document.querySelectorAll('[data-setting-field]').forEach(function (field) {
    const mode = field.querySelector('[data-value-mode]');
    const input = field.querySelector('[data-setting-input]');
    const picker = field.querySelector('[data-color-picker]');
    mode.addEventListener('change', function () {
        if (mode.value === 'default') {
            input.value = mode.dataset.defaultValue;
            if (input.tagName === 'SELECT' && !input.value) input.selectedIndex = input.id === 'value-instagram_api_base_url' ? 0 : 1;
            if (picker && /^#[0-9a-fA-F]{6}$/.test(input.value)) picker.value = input.value;
        }
    });
    input.addEventListener('input', function () {
        mode.value = 'custom';
        if (picker && /^#[0-9a-fA-F]{6}$/.test(input.value)) picker.value = input.value;
    });
    input.addEventListener('change', function () { mode.value = 'custom'; });
    if (picker) picker.addEventListener('input', function () { input.value = picker.value; mode.value = 'custom'; });
});
</script>
@endif
@endsection
