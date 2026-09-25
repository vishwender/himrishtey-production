@extends('layouts.dashboard')

@php
$statsTitle = match ($profileFor) {
'likes' => 'Profiles you liked',
'contacts' => 'Contacts you viewed',
'profile_viewed' => 'People who viewed your profile',
default => ucfirst($profileFor) . ' Profiles',
};
@endphp

@section('title', $statsTitle . ' - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/viewed-contact.css') }}" />
@endsection

@section('content')

<main class="vc-main">

    <div class="vc-header">
        <h1 class="vc-title">{{ $statsTitle }}</h1>
        <p class="vc-subtitle">{{ count($users) }} profiles</p>
    </div>

    <div class="vc-grid">

        @forelse($users as $member)

        <a href="{{ route('view-profile', $member['profile_id']) }}" class="vc-card">

            <div class="vc-image-wrap">

                <img
                    src="{{ asset('photos/photo/' . $member['photo']) }}"
                    class="vc-image"
                    alt="{{ $member['full_name'] }}">

                @if($member['member_type'] == 'verified')
                <span class="verified-badge">
                    <i data-lucide="badge-check"></i>
                </span>
                @endif

            </div>

            <div class="vc-content">

                <h3 class="vc-name">
                    {{ $member['full_name'] }}
                </h3>

                <div class="vc-location">
                    <i data-lucide="map-pin"></i>

                    {{ $member['city_living_in'] }},
                    {{ $member['state_living_in'] }}
                </div>

                <div class="vc-profession">

                    <i data-lucide="briefcase"></i>

                    {{ $member['occupation'] }}
                    •

                    {{ $member['age_years'] }} yrs

                    •

                    {{ $member['height'] }} Ft

                </div>

                <div class="vc-tags">

                    <span>{{ $member['religion'] }}</span>

                    <span>{{ $member['height'] }}</span>

                    <span>{{ $member['education'] }}</span>

                </div>

            </div>

        </a>

        @empty
        <div class="vc-empty" role="status">
            <h2>{{ $profileFor === 'likes' ? 'No liked profiles to show yet' : 'No profiles to show yet' }}</h2>
            <p>{{ $profileFor === 'likes' ? 'Profiles you like will appear here while they are active and visible.' : 'Check back later for more profiles.' }}</p>
        </div>
        @endforelse

    </div>

</main>
@endsection