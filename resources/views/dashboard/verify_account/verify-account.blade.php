@extends('layouts.dashboard')

@section('title', 'Verify account - ' . $siteName)

@section('styles')
<link href="{{ asset('assets/css/verify-account.css') }}" rel="stylesheet" />
@endsection

@section('content')
<main class="verify-page">
    <div class="verify-container">


        <!-- =====================================================
             LEFT SIDE
        ====================================================== -->

        <section class="verify-left">


            <!-- Back Button -->

            <button
                type="button"
                class="back-button"
                onclick="history.back()"
                aria-label="Go back">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <line
                        x1="19"
                        y1="12"
                        x2="5"
                        y2="12">
                    </line>

                    <polyline
                        points="12 19 5 12 12 5">
                    </polyline>

                </svg>

            </button>


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
                    Verify Mobile
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
                        for="phoneNumber">

                        Enter Phone number

                    </label>


                    <div class="phone-input-wrap">

                        <span class="country-code">
                            +91
                        </span>

                        <input
                            type="tel"
                            id="verifyPhoneNumber"
                            name="phone"
                            class="phone-input"
                            placeholder="Phone number"
                            maxlength="10"
                            inputmode="numeric"
                            autocomplete="tel"
                            value="{{ $member_mobile }}">

                    </div>


                    <span
                        class="form-error"
                        id="phoneError">
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
                            class="otp-input"
                            maxlength="4"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            placeholder="••••">


                        <span
                            class="form-error"
                            id="otpError">
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

                    🔒 Your mobile number is secure with us.

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
