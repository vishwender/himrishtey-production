@extends('layouts.dashboard')

@php
$collection = match ($profileFor) {
    'recent' => ['Recent Profiles', 'YOUR NEXT CONNECTION', 'A little curiosity.','A world of possibilities.', 'Explore profiles and discover someone you would like to know better.', 'sparkles'],
    'verified' => ['Verified Profiles', 'MEET VERIFIED MEMBERS', 'Meaningful connections.','A little more confidence.', 'Explore verified member profiles and take the next step when it feels right.', 'badge-check'],
    'viewed', 'profile_viewed' => ['Who Viewed My Profile', 'YOU CAUGHT THEIR ATTENTION', 'Someone noticed you.','Discover who they are.', 'Get to know the people who have taken a closer look at your profile.', 'eye'],
    'shortlist' => ['Shortlisted Profiles', 'SAVED FOR A REASON', 'A few favourites.','So many possibilities.', 'Revisit the profiles you saved and pick up where your curiosity left off.', 'bookmark'],
    'matching' => ['Matching Profiles', 'CHOSEN BY YOUR PREFERENCES', 'Shared preferences.','A promising beginning.', 'Explore profiles that align with the partner preferences you have set.', 'heart-handshake'],
    'likes' => ['People Who Liked Your Profile', 'A LITTLE INTEREST IN YOU', 'A simple like.','A possible connection.', 'Explore the people who have shown interest by liking your profile.', 'heart'],
    'contacts' => ['Viewed Contacts', 'TAKE THE NEXT STEP', 'From a profile.','To a conversation.', 'Revisit the profiles whose contact details you have viewed.', 'contact-round'],
    default => ['Profiles', 'EXPLORE YOUR COMMUNITY', 'Your next chapter.','Starts with a connection.', 'Explore profiles and save the people you would like to know better.', 'users'],
};
@endphp

@section('title', $collection[0] . ' - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/viewed-contact.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/recent-profiles.css') }}?v={{ filemtime(public_path('assets/css/recent-profiles.css')) }}">
@endsection

@section('content')
<section class="vc-main rp-page" aria-labelledby="profile-list-title">
    <a class="rp-back" href="{{ route('home') }}"><i data-lucide="arrow-left" aria-hidden="true"></i> Back to dashboard</a>
    <header class="rp-header">
        <span class="rp-eyebrow"><i data-lucide="{{ $collection[5] }}" aria-hidden="true"></i> {{ $collection[1] }}</span>
        <h1 id="profile-list-title">{{ $collection[2] }}<br><span>{{ $collection[3] }}</span></h1>
        <p>{{ $collection[4] }}</p>
    </header>
    <nav class="rp-collections" aria-label="Profile collections">
        @foreach(['recent' => 'Recent', 'verified' => 'Verified', 'viewed' => 'Who viewed me', 'shortlist' => 'Shortlisted', 'matching' => 'Matching'] as $key => $label)
        <a href="{{ route('recent-profiles', ['profile' => $key]) }}" @if($profileFor === $key || ($key === 'viewed' && $profileFor === 'profile_viewed')) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>
    <div class="rp-results-heading">
        <div><span class="rp-overline">YOUR COLLECTION</span><h2>{{ $collection[0] }} <span>{{ count($users) }}</span></h2></div>
        <a class="rp-search-link" href="{{ route('quick-search') }}"><i data-lucide="sliders-horizontal" aria-hidden="true"></i> Search profiles</a>
    </div>
    <div class="vc-grid">
        @forelse($users as $member)
        @include('dashboard.partials.profile-card', ['profile' => $member])
        @empty
        <div class="vc-empty" role="status">
            <span class="rp-empty-icon"><i data-lucide="{{ $collection[5] }}" aria-hidden="true"></i></span>
            <h2>{{ $profileFor === 'shortlist' ? 'Your shortlist starts here' : 'No profiles to show yet' }}</h2>
            <p>{{ $profileFor === 'shortlist' ? 'Tap the bookmark on a profile to save it to this collection.' : 'Check back later or explore more profiles with a quick search.' }}</p>
            <a class="rp-search-link" href="{{ route('quick-search') }}">Explore profiles <i data-lucide="arrow-right" aria-hidden="true"></i></a>
        </div>
        @endforelse
    </div>
</section>
@endsection
