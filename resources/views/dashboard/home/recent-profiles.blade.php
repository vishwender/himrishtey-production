<!-- Recent Profiles -->
<section class="profile-row-section container-xxl" aria-label="Recent Profiles">
    <div class="section-header">
        <div class="section-title-group">
            <h2 class="section-title">Recent Profiles</h2>
            <span class="section-badge">New</span>
        </div>
        <a href="{{ route('recent-profiles', ['profile' => 'recent']) }}" class="section-view-all">View All <i data-lucide="arrow-right" width="14" height="14"></i></a>
    </div>
    <div class="profile-scroll-track" role="list">
        @foreach(($recents ?? []) as $profile)
        @include('dashboard.partials.profile-card', ['profile' => $profile])
        @endforeach
    </div>
</section>