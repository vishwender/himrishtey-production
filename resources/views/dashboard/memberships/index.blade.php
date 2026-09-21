@extends('layouts.dashboard')

@section('title', 'Memberships - HimRishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/memberships.css') }}?v={{ filemtime(public_path('assets/css/memberships.css')) }}" />
@endsection

@section('content')
<main class="membership-section">

    <div class="membership-header">
        <h1>Membership</h1>
        <p>Choose the plan that suits your needs.</p>
    </div>
    <section class="membership-grid">
        @foreach($data['memberships'] as $membership)
        <a href="{{ route('plans', $membership->id) }}" class="membership-link">
            <article class="membership-card">
                <h2>{{ $membership->plan_name }}</h2>

                <p>
                    {{ $membership->plan_description }}
                </p>
            </article>
        </a>
        @endforeach
    </section>

    <section class="discussion-section">

        <div class="discussion-header">
            <h2>Need a discussion?</h2>
            <p>
                Our relationship experts are here to help you choose the right
                membership and answer your questions.
            </p>
        </div>

        <button id="callbackBtn" class="callback-btn" data-url="{{ route('callback.request') }}" data-status-url="{{ route('callback.status') }}">
            Request a Callback
        </button>

        <div class="discussion-timer">

            <div class="timer-icon">
                <i class="bi bi-stopwatch-fill"></i>
            </div>

            <div class="timer-text">
                <span id="timer">10:00</span>
            </div>

            <div class="timer-description">
                Need a discussion? Press the button above and we will contact
                you within 10 minutes.
            </div>

        </div>

    </section>

</main>
@endsection

@section('scripts')
<script src="{{asset('assets/js/memberships.js') }}"></script>
@endsection
