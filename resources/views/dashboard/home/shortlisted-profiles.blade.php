<!-- ShortListed Profile -->
<section class="profile-row-section container-xxl" aria-label="Who Viewed My Profile">
    <div class="section-header">
        <div class="section-title-group">
            <h2 class="section-title">Shortlisted Profiles</h2>

        </div>
        <a href="{{ route('recent-profiles', ['profile' => 'shortlist']) }}" class="section-view-all">View All <i data-lucide="arrow-right" width="14" height="14"></i></a>
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