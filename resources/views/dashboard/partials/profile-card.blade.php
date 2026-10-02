@php
$profileName = $profile['full_name'] ?? $profile['name'] ?? 'Profile';
$profileAge = $profile['age_years'] ?? $profile['age'] ?? null;
$location = $profile['location'] ?? trim(implode(', ', array_filter([$profile['city_living_in'] ?? null, $profile['state_living_in'] ?? null])));
$verified = ($profile['mem_type'] ?? '') === 'Yes' || strtolower($profile['member_type'] ?? '') === 'verified' || ($profile['verified'] ?? false);
$profileUrl = route('view-profile', $profile['profile_id']);
$details = [
['map-pin', 'Location', $location],
['briefcase-business', 'Occupation', $profile['occupation'] ?? null],
['sun', 'Religion', $profile['religion'] ?? null],
['ruler', 'Height', $profile['height'] ?? null],
];
@endphp
<div class="profile-card-link" data-profile-card-id="{{ $profile['id'] }}">
    <article class="profile-card dashboard-profile-card">
        <div class="profile-card-img-wrap">
            <a href="{{ $profileUrl }}" aria-label="View {{ $profileName }} profile">
                <img src="{{ \App\Website\Services\ProfilePhotoUrl::get($profile['photo'] ?? null) ?? asset('images/profile_photos/' . (strtolower($profile['gender'] ?? '') === 'female' ? 'girl.jpg' : 'boy.jpg')) }}" alt="{{ $profileName }}" width="250" height="300" loading="lazy" class="profile-card-img">
            </a>
            @if($verified)
            <span class="dashboard-card-verified">
                <i data-lucide="badge-check" aria-hidden="true"></i> Verified
            </span>
            @endif
        </div>
        <div class="profile-card-body">
            <h3 class="profile-card-name"><a href="{{ $profileUrl }}">{{ $profileName }}@if($profileAge !== null), {{ $profileAge }}@endif</a></h3>
            <dl class="dashboard-card-details">
                @foreach($details as [$icon, $label, $value])
                <div>
                    <dt>
                        <i data-lucide="{{ $icon }}" aria-hidden="true"></i>
                        <span class="visually-hidden">{{ $label }}</span>
                    </dt>
                    <dd>{{ filled($value) ? $value : $label . ' not specified' }}</dd>
                </div>
                @endforeach
            </dl>
        </div>
    </article>
</div>