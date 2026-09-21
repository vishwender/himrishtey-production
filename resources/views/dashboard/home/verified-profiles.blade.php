<!-- Verified Profiles -->
<section class="profile-row-section container-xxl" aria-label="Verified Profiles">
    <div class="section-header">
        <div class="section-title-group">
            <h2 class="section-title">Verified Profiles</h2>
            <span class="section-badge verified"><i data-lucide="shield-check" width="11" height="11"></i> Verified</span>
        </div>
        <a href="{{ route('recent-profiles', ['profile' => 'verified']) }}" class="section-view-all">View All <i data-lucide="arrow-right" width="14" height="14"></i></a>
    </div>
    <div class="profile-scroll-track" role="list">
        @foreach(($verifiedProfiles ?? []) as $profile)
        @include('dashboard.partials.profile-card', ['profile' => $profile])
        @endforeach
    </div>
</section>