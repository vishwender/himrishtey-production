<!-- Recent Profiles -->
<section class="profile-row-section matching-bg container-xxl home-profile-section" aria-label="Recent Profiles">
    <div class="section-header">
        <div class="section-title-group">
            <span class="home-section-eyebrow"><i data-lucide="sparkles" aria-hidden="true"></i> NEW CONNECTIONS</span>
            <h2 class="section-title">Recent Profiles</h2>
            <p class="home-section-description">Discover new people beginning their journey.</p>
            <span class="section-badge">New</span>
        </div>
        <a href="{{ route('recent-profiles', ['profile' => 'recent']) }}" class="section-view-all section-view-all-right">View All <i data-lucide="arrow-right" width="14" height="14"></i></a>
    </div>
    <div class="profile-scroll-track" role="list">
        @foreach(($recents ?? []) as $profile)
        @include('dashboard.partials.profile-card', ['profile' => $profile])
        @endforeach
    </div>
</section>