<!-- Matching Profiles -->
<section class="profile-row-section matching-bg container-xxl" aria-label="Matching Profiles">
    <div class="section-header">
        <div class="section-title-group">
            <h2 class="section-title">Matching Profiles</h2>
            <span class="section-badge primary">For You</span>
        </div>
        <a href="{{ route('recent-profiles', ['profile' => 'matching']) }}" class="section-view-all">View All <i data-lucide="arrow-right" width="14" height="14"></i></a>
    </div>
    <div class="profile-scroll-track" role="list">

        @forelse($data['matching_profiles'] as $profile)

        @include('dashboard.partials.profile-card', [
        'profile' => $profile,
        'match' => true
        ])

        @empty
        <div class="profile-empty-state">

            <div class="profile-empty-icon">
                <i data-lucide="users" width="48" height="48"></i>
            </div>

            <h3>No matching profiles yet</h3>

            <p>
                No profiles match your criteria.
                Try updating your preferences to see more matches.
            </p>

            <a href="{{ route('edit-profile') }}" class="btn btn-primary">
                Complete Profile
            </a>

        </div>

        @endforelse

    </div>
    </div>
</section>