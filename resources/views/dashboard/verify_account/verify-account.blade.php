@extends('layouts.dashboard')

@section('title', 'Verify account - ' . $siteName)

@section('styles')
<link href="{{ asset('assets/css/verify-account.css') }}?v={{ filemtime(public_path('assets/css/verify-account.css')) }}" rel="stylesheet" />
@endsection

@section('content')
<main class="verify-page">
    <div class="verify-container">


        <!-- =====================================================
             LEFT SIDE
        ====================================================== -->

        <section class="verify-left">


            <!-- Brand -->

            <div class="brand">

                <div class="brand-name">
                    {{ $siteName }}
                </div>

                <div class="brand-tagline">
                    Find your perfect life partner
                </div>

            </div>


            <!-- Wedding Artwork -->

            <div class="wedding-art">

                <img
                    src="{{ asset('assets/images/verify-mobile-art.png') }}"
                    alt="Wedding illustration">

            </div>


        </section>


        <!-- =====================================================
             RIGHT SIDE
        ====================================================== -->

        <section class="verify-right">

            <div class="verify-card">


                <!-- Title -->

                <h1 class="verify-title">
                    Verify your mobile number
                </h1>


                <!-- Description -->

                <p class="verify-description">
                    Enter your registered mobile number to
                    receive a one-time password.
                </p>


                <!-- Phone Number -->

                <div class="form-group">

                    <label
                        class="form-label"
                        for="verifyPhoneNumber">

                        Mobile number

                    </label>


                    <div class="phone-input-wrap">

                        <span class="country-code">
                            +91
                        </span>

                        <input
                            type="tel"
                            id="verifyPhoneNumber"
                            name="phone"
                            aria-describedby="phoneError"
                            class="phone-input"
                            placeholder="Phone number"
                            maxlength="10"
                            inputmode="numeric"
                            autocomplete="tel"
                            value="{{ $member_mobile }}">

                    </div>


                    <span
                        class="form-error"
                        id="phoneError" role="alert">
                    </span>

                </div>


                <!-- Send OTP -->

                <button
                    type="button"
                    id="sendVerifyOtpBtn"
                    class="send-otp-btn">

                    Send OTP

                </button>


                <!-- =================================================
                     OTP SECTION
                ================================================== -->

                <div
                    class="otp-section"
                    id="otpSection">


                    <div class="form-group">

                        <label
                            class="form-label"
                            for="otp">

                            Enter OTP

                        </label>


                        <input
                            type="text"
                            id="otp"
                            aria-describedby="otpError"
                            class="otp-input"
                            maxlength="4"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            placeholder="••••">


                        <span
                            class="form-error"
                            id="otpError" role="alert">
                        </span>

                    </div>


                    <!-- Timer -->

                    <div class="otp-info">

                        <span>
                            OTP expires in
                            <strong id="otpTimer">
                                05:00
                            </strong>
                        </span>


                        <button
                            type="button"
                            id="resendOtpBtn"
                            class="resend-otp"
                            disabled>

                            Resend OTP

                        </button>

                    </div>


                    <!-- Verify -->

                    <button
                        type="button"
                        id="verifyOtpBtn"
                        class="send-otp-btn"
                        style="margin-top: 25px;">

                        Verify OTP

                    </button>


                </div>


                <!-- Security -->

                <div class="secure-text">

                    <i data-lucide="shield-check" width="16" height="16" aria-hidden="true"></i> Verify your number to help keep your account secure.

                </div>


            </div>

        </section>


    </div>

    <script>

    </script>

</main>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/verify-account.js') }}"></script>
@endsection
