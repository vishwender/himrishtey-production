@php
$profileName = $profile['full_name'] ?? $profile['name'] ?? 'Profile';
$profileAge = !empty($profile['age_years']) ? $profile['age_years'] . ' yrs' : null;
$city = $profile['city_living_in'] ?? null;
$state = $profile['state_living_in'] ?? null;
$country = $profile['country_living_in'] ?? null;
$location = trim(implode(', ', array_filter([$city, $state, $country])));
$occupation = $profile['occupation'] ?? null;
$religion = $profile['religion'] ?? null;
$height = $profile['height'] ?? null;
$education = $profile['education'] ?? null;
$verified = !empty($profile['mem_type']) && $profile['mem_type'] === 'Yes';
@endphp

<a href="{{ route('view-profile', $profile['profile_id']) }}"
    class="profile-card-link">

    <div class="profile-card">

        <div class="profile-card-img-wrap">
            <img src="{{ $profile['photo'] ?? 'https://picsum.photos/seed/profile/220/280' }}"
                alt="{{ $profileName }}"
                width="220"
                height="280"
                loading="lazy"
                class="profile-card-img" />

            @if($verified)
            <span class="profile-card-verified-badge">
                <i data-lucide="shield-check" width="12" height="12"></i>
            </span>
            @endif

        </div>

        <div class="profile-card-body">

            <h3 class="profile-card-name">
                {{ $profileName }}

                @if($verified)
                <i data-lucide="shield-check" width="13" height="13" class="verified-icon"></i>
                @endif
            </h3>

            @if($location)
            <p class="profile-card-meta">
                <i data-lucide="map-pin" width="12" height="12"></i>
                {{ $location }}
            </p>
            @endif

            @if($occupation || $profileAge)
            <p class="profile-card-meta">
                @if($occupation)
                <i data-lucide="briefcase" width="12" height="12"></i>
                {{ $occupation }}

                @if($profileAge)
                • {{ $profileAge }}
                @endif
                @else
                {{ $profileAge }}
                @endif
            </p>
            @endif

            <div class="profile-card-tags">
                @if($religion)
                <span class="pct">{{ $religion }}</span>
                @endif

                @if($height)
                <span class="pct">{{ $height }}</span>
                @endif

                @if($education)
                <span class="pct">{{ $education }}</span>
                @endif
            </div>

        </div>

    </div>

</a>
