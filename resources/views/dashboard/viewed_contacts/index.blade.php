@extends('layouts.dashboard')

@section('title', 'Viewed Contact - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/viewed-contact.css') }}" />
@endsection

@section('content')

<div class="vc-main">

    <div class="vc-header">
        <h1 class="vc-title">Viewed Contacts</h1>
        <p class="vc-subtitle">Profiles whose contact details you've viewed.</p>
    </div>

    <div class="vc-grid">

        @foreach($data['contacts'] as $member)

        @include('dashboard.partials.profile-card', ['profile' => $member])

        @endforeach

    </div>

</div>
@endsection