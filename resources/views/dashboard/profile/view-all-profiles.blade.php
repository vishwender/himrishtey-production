@extends('layouts.dashboard')

@php
$statsTitle = match ($profileFor) {
'likes' => 'People who liked your profile',
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

<div class="vc-main">

    <div class="vc-header">
        <h1 class="vc-title">{{ $statsTitle }}</h1>
        <p class="vc-subtitle">{{ count($users) }} profiles</p>
    </div>

    <div class="vc-grid">

        @forelse($users as $member)

        @include('dashboard.partials.profile-card', ['profile' => $member])

        @empty
        <div class="vc-empty" role="status">
            <h2>{{ $profileFor === 'likes' ? 'No liked profiles to show yet' : 'No profiles to show yet' }}</h2>
            <p>{{ $profileFor === 'likes' ? 'Profiles you like will appear here while they are active and visible.' : 'Check back later for more profiles.' }}</p>
        </div>
        @endforelse

    </div>

</div>
@endsection