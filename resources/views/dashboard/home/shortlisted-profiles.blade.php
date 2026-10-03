<!-- ShortListed Profile -->
<section class="profile-row-section matching-bg container-xxl home-profile-section" aria-label="Shortlisted Profiles">
    <div class="section-header">
        <div class="section-title-group">
            <span class="home-section-eyebrow"><i data-lucide="bookmark" aria-hidden="true"></i> KEEP THEM CLOSE</span>
            <h2 class="section-title">Shortlisted Profiles</h2>
            <p class="home-section-description">Revisit the people who caught your attention.</p>
        </div>
        <a href="{{ route('recent-profiles', ['profile' => 'shortlist']) }}" class="section-view-all section-view-all-right">View All <i data-lucide="arrow-right" width="14" height="14"></i></a>
    </div>
    <div class="profile-scroll-track" role="list">
        @forelse($shortlisted ?? [] as $profile)
        @include('dashboard.partials.profile-card', ['profile' => $profile])
        @empty
        <div class="profile-empty-state">

            <div class="profile-empty-icon">
                <i data-lucide="users" width="48" height="48"></i>
            </div>

            <h3>No Shortlisted profiles yet</h3>

            <p>
                Your have not shortlisted anyone yet.
                Keep your profile complete and engaging to attract more potential matches.
            </p>

            <a href="{{ route('edit-profile') }}" class="btn btn-primary">
                Complete Profile
            </a>

        </div>
        @endforelse
    </div>
</section>