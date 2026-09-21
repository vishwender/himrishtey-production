@extends('layouts.dashboard')

@section('title', ucfirst($profileFor) .' Profiles - Himrishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/viewed-contact.css') }}" />
@endsection

@section('content')

<main class="vc-main">

    <div class="vc-header">
        <h1 class="vc-title">{{ ucfirst($profileFor) }} Profiles</h1>
        <p class="vc-subtitle">{{ count($users) }} {{ ucfirst($profileFor) }} profiles</p>
    </div>

    <div class="vc-grid">

        @foreach($users as $member)

        <a href="{{ route('view-profile', $member['profile_id']) }}" class="vc-card">

            <div class="vc-image-wrap">

                <img
                    src="{{ $member['photo'] }}"
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

        @endforeach

    </div>

</main>
@endsection