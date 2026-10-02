/* Shared presentation for dynamically loaded website profile cards. */
(() => {
  const escape = value => String(value ?? '').replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));
  window.dashboardProfileCardMarkup = profile => {
    const name = profile.full_name || profile.name || [profile.first_name, profile.last_name].filter(Boolean).join(' ') || 'Profile';
    const age = profile.age_years ?? profile.age;
    const location = profile.location || [profile.city_living_in || profile.city, profile.state_living_in].filter(Boolean).join(', ');
    const verified = profile.verified || profile.mem_type === 'Yes' || String(profile.member_type).toLowerCase() === 'verified';
    const url = (window.dashboardProfileUrl || '/view-profile/__PROFILE__').replace('__PROFILE__', encodeURIComponent(profile.profile_id));
    const title = name + (age !== null && age !== undefined && age !== '' ? ', ' + age : '');
    const details = [['map-pin', 'Location', location], ['briefcase-business', 'Occupation', profile.occupation], ['sun', 'Religion', profile.religion], ['ruler', 'Height', profile.height]];
    return '<div class="profile-card-img-wrap"><a href="' + escape(url) + '" aria-label="View ' + escape(name) + ' profile">' +
      '<img src="' + escape(window.profilePhotoUrl(profile.photo_url || profile.photo, profile.gender)) + '" alt="' + escape(name) + '" width="250" height="300" loading="lazy" class="profile-card-img"></a>' +
      (verified ? '<span class="dashboard-card-verified"><i data-lucide="badge-check" aria-hidden="true"></i> Verified</span>' : '') +
      '</div><div class="profile-card-body"><h3 class="profile-card-name"><a href="' + escape(url) + '">' + escape(title) + '</a></h3>' +
      '<dl class="dashboard-card-details">' + details.map(([icon, label, value]) => '<div><dt><i data-lucide="' + icon + '" aria-hidden="true"></i><span class="visually-hidden">' + label + '</span></dt><dd>' + escape(value || label + ' not specified') + '</dd></div>').join('') + '</dl></div>';
  };
})();
