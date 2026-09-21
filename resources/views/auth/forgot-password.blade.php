@extends('layouts.public')
@section('title', 'Forgot Password - ' . $siteName)
@section('description', 'Securely reset your ' . $siteName . ' account password using your registered mobile number.')

@section('content')

<style>
    /* =========================================================
       FORGOT PASSWORD PAGE
    ========================================================== */

    .forgot-password-page {
        min-height: calc(100vh - 80px);
        background:
            linear-gradient(135deg,
                #fff8fb 0%,
                #fff1f6 45%,
                #fde7f0 100%);

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 60px 20px;
    }


    /* =========================================================
       CARD
    ========================================================== */

    .forgot-password-card {
        width: 100%;
        max-width: 500px;

        background: #ffffff;

        border-radius: 24px;

        padding: 42px 42px 38px;

        box-shadow:
            0 15px 50px rgba(218, 31, 102, 0.10);

        border: 1px solid #f8dce8;
    }


    /* =========================================================
       ICON
    ========================================================== */

    .forgot-password-icon {
        width: 72px;
        height: 72px;

        margin: 0 auto 20px;

        border-radius: 50%;

        background: #fde5ef;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #d92367;
    }


    .forgot-password-icon svg {
        width: 32px;
        height: 32px;
    }


    /* =========================================================
       HEADING
    ========================================================== */

    .forgot-password-title {
        text-align: center;

        font-size: 30px;
        font-weight: 700;

        color: #262626;

        margin: 0 0 10px;
    }


    .forgot-password-subtitle {
        text-align: center;

        font-size: 15px;
        line-height: 1.6;

        color: #777;

        margin: 0 auto 30px;

        max-width: 390px;
    }


    /* =========================================================
       STEPS
    ========================================================== */

    .forgot-steps {
        display: flex;
        align-items: center;

        margin-bottom: 30px;
    }


    .forgot-step {
        display: flex;
        align-items: center;

        flex: 1;
    }


    .forgot-step:last-child {
        flex: 0;
    }


    .forgot-step-circle {
        width: 34px;
        height: 34px;

        min-width: 34px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 13px;
        font-weight: 600;

        background: #f5f5f5;
        color: #999;

        border: 1px solid #e8e8e8;
    }


    .forgot-step.active .forgot-step-circle {
        background: #d92367;
        color: #ffffff;
        border-color: #d92367;
    }


    .forgot-step.completed .forgot-step-circle {
        background: #d92367;
        color: #ffffff;
        border-color: #d92367;
    }


    .forgot-step-label {
        margin-left: 8px;

        font-size: 12px;

        color: #999;

        white-space: nowrap;
    }


    .forgot-step.active .forgot-step-label,
    .forgot-step.completed .forgot-step-label {
        color: #d92367;
        font-weight: 600;
    }


    .forgot-step-line {
        height: 1px;

        background: #e8e8e8;

        flex: 1;

        margin: 0 10px;
    }


    /* =========================================================
       FORM GROUP
    ========================================================== */

    .forgot-form-group {
        margin-bottom: 20px;
    }


    .forgot-form-label {
        display: block;

        font-size: 14px;
        font-weight: 600;

        color: #333;

        margin-bottom: 8px;
    }


    .forgot-input-wrapper {
        position: relative;
    }


    .forgot-input-icon {
        position: absolute;

        left: 15px;
        top: 50%;

        transform: translateY(-50%);

        color: #aaa;

        pointer-events: none;
    }


    .forgot-input {
        width: 100%;

        height: 50px;

        border: 1px solid #e1dfe1;

        border-radius: 10px;

        padding: 0 15px 0 45px;

        font-size: 15px;

        color: #333;

        outline: none;

        transition: all 0.2s ease;

        background: #fff;
    }


    .forgot-input:focus {
        border-color: #d92367;

        box-shadow:
            0 0 0 3px rgba(217, 35, 103, 0.08);
    }


    .forgot-input::placeholder {
        color: #aaa;
    }


    .forgot-input:read-only {
        background: #f9f9f9;
        color: #777;
    }


    /* =========================================================
       ERROR
    ========================================================== */

    .forgot-error {
        display: block;

        font-size: 13px;

        color: #dc3545;

        margin-top: 6px;

        min-height: 18px;
    }


    /* =========================================================
       BUTTON
    ========================================================== */

    .forgot-btn {
        width: 100%;

        height: 50px;

        border: 0;

        border-radius: 10px;

        background: #d92367;

        color: #ffffff;

        font-size: 15px;
        font-weight: 600;

        cursor: pointer;

        transition: all 0.2s ease;
    }


    .forgot-btn:hover {
        background: #c51d5d;

        box-shadow:
            0 8px 20px rgba(217, 35, 103, 0.22);

        transform: translateY(-1px);
    }


    .forgot-btn:disabled {
        opacity: 0.65;

        cursor: not-allowed;

        transform: none;

        box-shadow: none;
    }


    /* =========================================================
       OTP
    ========================================================== */

    .otp-input {
        text-align: center;

        letter-spacing: 10px;

        font-size: 22px;
        font-weight: 600;

        padding-left: 20px;
    }


    .otp-timer-wrapper {
        display: flex;

        align-items: center;
        justify-content: space-between;

        margin-top: 8px;

        font-size: 13px;

        color: #888;
    }


    .otp-timer {
        color: #d92367;

        font-weight: 600;
    }


    .resend-btn {
        border: 0;

        background: none;

        padding: 0;

        color: #d92367;

        font-size: 13px;
        font-weight: 600;

        cursor: pointer;
    }


    .resend-btn:disabled {
        color: #aaa;

        cursor: not-allowed;
    }


    /* =========================================================
       PASSWORD SECTION
    ========================================================== */

    .password-note {
        background: #fff6f9;

        border: 1px solid #f8dce8;

        border-radius: 10px;

        padding: 12px 14px;

        font-size: 13px;

        color: #777;

        margin-bottom: 22px;
    }


    /* =========================================================
       BACK TO LOGIN
    ========================================================== */

    .back-login {
        text-align: center;

        margin-top: 25px;

        font-size: 14px;

        color: #777;
    }


    .back-login a {
        color: #d92367;

        font-weight: 600;

        text-decoration: none;
    }


    .back-login a:hover {
        text-decoration: underline;
    }


    /* =========================================================
       HIDDEN SECTIONS
    ========================================================== */

    #otpSection,
    #passwordSection {
        display: none;
    }


    /* =========================================================
       DARK MODE
    ========================================================== */

    [data-theme="dark"] .forgot-password-page {
        background: linear-gradient(135deg, #1f1118 0%, #170e13 48%, #120c0f 100%);
    }

    [data-theme="dark"] .forgot-password-card {
        background: var(--color-surface);
        border-color: var(--color-border);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.32);
    }

    [data-theme="dark"] .forgot-password-icon {
        background: var(--color-primary-soft);
        color: var(--color-primary);
    }

    [data-theme="dark"] :is(
        .forgot-password-title,
        .forgot-form-label
    ) {
        color: var(--color-text);
    }

    [data-theme="dark"] :is(
        .forgot-password-subtitle,
        .forgot-step-label,
        .otp-timer-wrapper,
        .password-note,
        .back-login
    ) {
        color: var(--color-text-muted);
    }

    [data-theme="dark"] .forgot-step-circle {
        background: var(--color-surface-2);
        border-color: var(--color-border);
        color: var(--color-text-muted);
    }

    [data-theme="dark"] .forgot-step-line {
        background: var(--color-divider);
    }

    [data-theme="dark"] .forgot-input {
        background: var(--color-surface-2);
        border-color: var(--color-border);
        color: var(--color-text);
    }

    [data-theme="dark"] .forgot-input::placeholder,
    [data-theme="dark"] .forgot-input-icon {
        color: var(--color-text-faint);
    }

    [data-theme="dark"] .forgot-input:read-only {
        background: var(--color-surface-3);
        color: var(--color-text-muted);
    }

    [data-theme="dark"] .password-note {
        background: var(--color-surface-2);
        border-color: var(--color-border);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 576px) {

        .forgot-password-page {
            padding: 35px 15px;
        }


        .forgot-password-card {
            padding: 30px 22px;

            border-radius: 18px;
        }


        .forgot-password-title {
            font-size: 25px;
        }


        .forgot-password-subtitle {
            font-size: 14px;
        }


        .forgot-step-label {
            display: none;
        }


        .forgot-step-line {
            margin: 0 7px;
        }

    }

    /* Align password recovery with the public authentication experience. */
    .public-site .forgot-password-page {
        --forgot-accent: var(--brand);
        min-height: 680px;
        padding: clamp(2.5rem, 6vw, 5rem) 1rem;
        background: radial-gradient(circle at 12% 10%, color-mix(in srgb, var(--forgot-accent) 12%, transparent), transparent 28rem), var(--color-bg);
    }
    .public-site .forgot-password-card {
        max-width: 530px;
        border-color: var(--color-border);
        border-radius: 2rem;
        background: var(--color-surface);
        box-shadow: var(--shadow-lg);
    }
    .public-site .forgot-brand { display: flex; justify-content: center; margin-bottom: 1.4rem; }
    .public-site .forgot-brand img { width: min(190px, 60vw); height: 54px; object-fit: contain; }
    .public-site .forgot-password-icon { width: 64px; height: 64px; color: var(--forgot-accent); background: color-mix(in srgb, var(--forgot-accent) 11%, transparent); }
    .public-site .forgot-password-title { font-family: var(--font-display); color: var(--color-text); font-size: clamp(1.9rem, 4vw, 2.4rem); }
    .public-site .forgot-password-subtitle { color: var(--color-text-muted); }
    .public-site .forgot-step.active .forgot-step-circle,
    .public-site .forgot-step.completed .forgot-step-circle,
    .public-site .forgot-btn { background: var(--forgot-accent); border-color: var(--forgot-accent); }
    .public-site .forgot-step.active .forgot-step-label,
    .public-site .forgot-step.completed .forgot-step-label,
    .public-site .otp-timer,
    .public-site .resend-btn,
    .public-site .back-login a { color: var(--forgot-accent); }
    .public-site .forgot-input { color: var(--color-text); background: var(--color-bg); border-color: var(--color-border); }
    .public-site .forgot-input:focus { border-color: var(--forgot-accent); box-shadow: 0 0 0 3px color-mix(in srgb, var(--forgot-accent) 14%, transparent); }
    .public-site .forgot-btn { border-radius: var(--radius-full); }
    .public-site .forgot-btn:hover { filter: brightness(.9); box-shadow: 0 9px 24px color-mix(in srgb, var(--forgot-accent) 25%, transparent); }
    .public-site .password-note { color: var(--color-text-muted); border-color: var(--color-border); background: var(--color-surface-2); }
    @media (max-width: 576px) {
        .public-site .forgot-password-page { min-height: 0; padding: .75rem 0 2rem; background: var(--color-surface); }
        .public-site .forgot-password-card { border: 0; border-radius: 0; box-shadow: none; }
    }
</style>


<main>

    <section class="forgot-password-page">

        <div class="forgot-password-card">

            <a class="forgot-brand" href="{{ route('welcome') }}" aria-label="{{ $siteName }} home">
                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}">
            </a>


            <!-- =================================================
                 ICON
            ================================================== -->

            <div class="forgot-password-icon">

                <i
                    data-lucide="lock-keyhole">
                </i>

            </div>


            <!-- =================================================
                 HEADING
            ================================================== -->

            <h1 class="forgot-password-title">
                Forgot Password?
            </h1>


            <p class="forgot-password-subtitle">
                Don't worry! Enter your registered mobile
                number and we'll help you reset your password.
            </p>


            <!-- =================================================
                 STEPS
            ================================================== -->

            <div class="forgot-steps">

                <div class="forgot-step active">

                    <div class="forgot-step-circle">
                        1
                    </div>

                    <span class="forgot-step-label">
                        Mobile
                    </span>

                </div>


                <div class="forgot-step-line"></div>


                <div class="forgot-step">

                    <div class="forgot-step-circle">
                        2
                    </div>

                    <span class="forgot-step-label">
                        Verify
                    </span>

                </div>


                <div class="forgot-step-line"></div>


                <div class="forgot-step">

                    <div class="forgot-step-circle">
                        3
                    </div>

                    <span class="forgot-step-label">
                        Reset
                    </span>

                </div>

            </div>


            <!-- =================================================
                 MOBILE SECTION
            ================================================== -->

            <div id="mobileSection">

                <div class="forgot-form-group">

                    <label
                        for="mobileNumber"
                        class="forgot-form-label">

                        Registered Mobile Number

                    </label>


                    <div class="forgot-input-wrapper">

                        <i
                            data-lucide="smartphone"
                            class="forgot-input-icon"
                            width="18"
                            height="18">
                        </i>


                        <input
                            type="text"
                            id="mobileNumber"
                            class="forgot-input"
                            maxlength="10"
                            inputmode="numeric"
                            autocomplete="tel"
                            placeholder="Enter 10 digit mobile number">

                    </div>


                    <span
                        id="mobileError"
                        class="forgot-error">
                    </span>

                </div>


                <button
                    type="button"
                    id="sendOtpBtn"
                    class="forgot-btn public-cta public-cta-primary">

                    Send OTP

                </button>

            </div>


            <!-- =================================================
                 OTP SECTION
            ================================================== -->

            <div id="otpSection">

                <div class="forgot-form-group">

                    <label
                        for="otp"
                        class="forgot-form-label">

                        Enter OTP

                    </label>


                    <div class="forgot-input-wrapper">

                        <i
                            data-lucide="shield-check"
                            class="forgot-input-icon"
                            width="18"
                            height="18">
                        </i>


                        <input
                            type="text"
                            id="otp"
                            class="forgot-input otp-input"
                            maxlength="4"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            placeholder="••••">

                    </div>


                    <span
                        id="otpError"
                        class="forgot-error">
                    </span>


                    <div class="otp-timer-wrapper">

                        <span>
                            OTP expires in
                            <strong
                                id="otpTimer"
                                class="otp-timer">
                                05:00
                            </strong>
                        </span>


                        <button
                            type="button"
                            id="resendOtpBtn"
                            class="resend-btn"
                            disabled>

                            Resend OTP

                        </button>

                    </div>

                </div>


                <button
                    type="button"
                    id="verifyOtpBtn"
                    class="forgot-btn public-cta public-cta-primary">

                    Verify OTP

                </button>

            </div>


            <!-- =================================================
                 PASSWORD SECTION
            ================================================== -->

            <div id="passwordSection">

                <div class="password-note">

                    <i
                        data-lucide="shield-check"
                        width="15"
                        height="15">
                    </i>

                    OTP verified. Please create a new
                    password for your account.

                </div>


                <div class="forgot-form-group">

                    <label
                        for="password"
                        class="forgot-form-label">

                        New Password

                    </label>


                    <div class="forgot-input-wrapper">

                        <i
                            data-lucide="lock"
                            class="forgot-input-icon"
                            width="18"
                            height="18">
                        </i>


                        <input
                            type="password"
                            id="password"
                            class="forgot-input"
                            autocomplete="new-password"
                            placeholder="Enter new password">

                    </div>

                </div>


                <div class="forgot-form-group">

                    <label
                        for="passwordConfirmation"
                        class="forgot-form-label">

                        Confirm New Password

                    </label>


                    <div class="forgot-input-wrapper">

                        <i
                            data-lucide="lock-keyhole"
                            class="forgot-input-icon"
                            width="18"
                            height="18">
                        </i>


                        <input
                            type="password"
                            id="passwordConfirmation"
                            class="forgot-input"
                            autocomplete="new-password"
                            placeholder="Confirm new password">

                    </div>


                    <span
                        id="passwordError"
                        class="forgot-error">
                    </span>

                </div>


                <button
                    type="button"
                    id="resetPasswordBtn"
                    class="forgot-btn public-cta public-cta-primary">

                    Reset Password

                </button>

            </div>


            <!-- =================================================
                 BACK TO LOGIN
            ================================================== -->

            <div class="back-login">

                Remember your password?

                <a href="{{ url('/login') }}">
                    Login
                </a>

            </div>


        </div>

    </section>

</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    /*
     * Initialize Lucide icons if your site uses Lucide.
     */
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* =========================================================
           API ROUTES
        ========================================================== */

        const SEND_OTP_URL = '/forgot-password/send-otp';
        const VERIFY_OTP_URL = '/forgot-password/verify-otp';
        const RESET_PASSWORD_URL = '/forgot-password/reset';


        /* =========================================================
           ELEMENTS
        ========================================================== */

        const mobileSection =
            document.getElementById('mobileSection');

        const otpSection =
            document.getElementById('otpSection');

        const passwordSection =
            document.getElementById('passwordSection');


        const mobileInput =
            document.getElementById('mobileNumber');

        const otpInput =
            document.getElementById('otp');

        const passwordInput =
            document.getElementById('password');

        const passwordConfirmationInput =
            document.getElementById('passwordConfirmation');


        const sendOtpBtn =
            document.getElementById('sendOtpBtn');

        const verifyOtpBtn =
            document.getElementById('verifyOtpBtn');

        const resendOtpBtn =
            document.getElementById('resendOtpBtn');

        const resetPasswordBtn =
            document.getElementById('resetPasswordBtn');


        const mobileError =
            document.getElementById('mobileError');

        const otpError =
            document.getElementById('otpError');

        const passwordError =
            document.getElementById('passwordError');

        const otpTimer =
            document.getElementById('otpTimer');


        const stepItems =
            document.querySelectorAll('.forgot-step');


        /* =========================================================
           VARIABLES
        ========================================================== */

        let timerInterval = null;

        let remainingSeconds = 300;

        let currentMobile = '';


        /* =========================================================
           CSRF TOKEN
        ========================================================== */

        const csrfTokenElement =
            document.querySelector(
                'meta[name="csrf-token"]'
            );

        const csrfToken =
            csrfTokenElement ?
            csrfTokenElement.getAttribute('content') :
            '';


        /* =========================================================
           INITIALIZE LUCIDE
        ========================================================== */

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }


        /* =========================================================
           SHOW ERROR
        ========================================================== */

        function showError(element, message) {

            if (element) {
                element.textContent = message || '';
            }

        }


        /* =========================================================
           CLEAR ERRORS
        ========================================================== */

        function clearErrors() {

            showError(mobileError, '');

            showError(otpError, '');

            showError(passwordError, '');

        }


        /* =========================================================
           VALIDATE MOBILE
        ========================================================== */

        function validateMobile() {

            clearErrors();

            const mobile =
                mobileInput.value.trim();


            if (!mobile) {

                showError(
                    mobileError,
                    'Please enter your mobile number.'
                );

                mobileInput.focus();

                return false;
            }


            if (!/^[6-9][0-9]{9}$/.test(mobile)) {

                showError(
                    mobileError,
                    'Please enter a valid 10 digit mobile number.'
                );

                mobileInput.focus();

                return false;
            }


            return true;
        }


        /* =========================================================
           VALIDATE OTP
        ========================================================== */

        function validateOtp() {

            showError(otpError, '');

            const otp =
                otpInput.value.trim();


            if (!otp) {

                showError(
                    otpError,
                    'Please enter the OTP.'
                );

                otpInput.focus();

                return false;
            }


            if (!/^[0-9]{4}$/.test(otp)) {

                showError(
                    otpError,
                    'Please enter the 4 digit OTP.'
                );

                otpInput.focus();

                return false;
            }


            return true;
        }


        /* =========================================================
           VALIDATE PASSWORD
        ========================================================== */

        function validatePassword() {

            showError(passwordError, '');

            const password =
                passwordInput.value;

            const confirmation =
                passwordConfirmationInput.value;


            if (!password) {

                showError(
                    passwordError,
                    'Please enter a new password.'
                );

                passwordInput.focus();

                return false;
            }


            if (password.length < 6) {

                showError(
                    passwordError,
                    'Password must be at least 6 characters.'
                );

                passwordInput.focus();

                return false;
            }


            if (!confirmation) {

                showError(
                    passwordError,
                    'Please confirm your password.'
                );

                passwordConfirmationInput.focus();

                return false;
            }


            if (password !== confirmation) {

                showError(
                    passwordError,
                    'Passwords do not match.'
                );

                passwordConfirmationInput.focus();

                return false;
            }


            return true;
        }


        /* =========================================================
           UPDATE STEPS
        ========================================================== */

        function setStep(step) {

            stepItems.forEach(function(item, index) {

                item.classList.remove('active');
                item.classList.remove('completed');

                const stepNumber =
                    index + 1;


                if (stepNumber < step) {

                    item.classList.add('completed');

                } else if (stepNumber === step) {

                    item.classList.add('active');

                }

            });

        }


        /* =========================================================
           SHOW MOBILE SECTION
        ========================================================== */

        function showMobileSection() {

            mobileSection.style.display = 'block';

            otpSection.style.display = 'none';

            passwordSection.style.display = 'none';

            setStep(1);

        }


        /* =========================================================
           SHOW OTP SECTION
        ========================================================== */

        function showOtpSection() {

            mobileSection.style.display = 'none';

            otpSection.style.display = 'block';

            passwordSection.style.display = 'none';

            setStep(2);

            otpInput.value = '';

            otpInput.focus();

        }


        /* =========================================================
           SHOW PASSWORD SECTION
        ========================================================== */

        function showPasswordSection() {

            mobileSection.style.display = 'none';

            otpSection.style.display = 'none';

            passwordSection.style.display = 'block';

            setStep(3);

            passwordInput.value = '';

            passwordConfirmationInput.value = '';

            passwordInput.focus();

        }


        /* =========================================================
           START OTP TIMER
        ========================================================== */

        function startOtpTimer(seconds = 300) {

            clearInterval(timerInterval);

            remainingSeconds = seconds;

            resendOtpBtn.disabled = true;


            updateTimer();


            timerInterval =
                setInterval(function() {

                    remainingSeconds--;

                    updateTimer();


                    if (remainingSeconds <= 0) {

                        clearInterval(timerInterval);

                        resendOtpBtn.disabled = false;

                        otpTimer.textContent =
                            '00:00';

                    }

                }, 1000);
        }


        /* =========================================================
           UPDATE TIMER
        ========================================================== */

        function updateTimer() {

            const minutes =
                Math.floor(
                    remainingSeconds / 60
                );

            const seconds =
                remainingSeconds % 60;


            otpTimer.textContent =
                String(minutes).padStart(2, '0') +
                ':' +
                String(seconds).padStart(2, '0');

        }


        /* =========================================================
           STOP TIMER
        ========================================================== */

        function stopOtpTimer() {

            clearInterval(timerInterval);

            timerInterval = null;

        }


        /* =========================================================
           SEND OTP
        ========================================================== */

        async function sendOtp() {

            if (!validateMobile()) {
                return;
            }


            const mobile =
                mobileInput.value.trim();


            currentMobile = mobile;


            sendOtpBtn.disabled = true;

            sendOtpBtn.textContent =
                'Sending OTP...';


            try {

                const response =
                    await fetch(
                        SEND_OTP_URL, {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': csrfToken
                            },

                            body: JSON.stringify({
                                mobile_number: mobile
                            })
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Unable to send OTP.'
                    );
                }


                /*
                 * OTP successfully sent
                 */

                showOtpSection();

                startOtpTimer(300);


                Swal.fire({
                    title: 'OTP Sent!',
                    text: data.message ||
                        'OTP has been sent to your mobile number.',
                    icon: 'success',
                    confirmButtonColor: '#d92367'
                });


            } catch (error) {

                showError(
                    mobileError,
                    error.message ||
                    'Unable to send OTP. Please try again.'
                );


                sendOtpBtn.disabled = false;

                sendOtpBtn.textContent =
                    'Send OTP';

            }

        }


        /* =========================================================
           SEND OTP BUTTON
        ========================================================== */

        sendOtpBtn.addEventListener(
            'click',
            function() {

                sendOtp();

            }
        );


        /* =========================================================
           RESEND OTP
        ========================================================== */

        resendOtpBtn.addEventListener(
            'click',
            function() {

                if (resendOtpBtn.disabled) {
                    return;
                }


                sendOtp();

            }
        );


        /* =========================================================
           VERIFY OTP
        ========================================================== */

        async function verifyOtp() {

            if (!validateOtp()) {
                return;
            }


            const otp =
                otpInput.value.trim();


            verifyOtpBtn.disabled = true;

            verifyOtpBtn.textContent =
                'Verifying...';


            try {

                const response =
                    await fetch(
                        VERIFY_OTP_URL, {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': csrfToken
                            },

                            body: JSON.stringify({

                                mobile_number: currentMobile,

                                otp: otp

                            })
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Invalid OTP.'
                    );
                }


                /*
                 * OTP verified
                 */

                stopOtpTimer();

                showPasswordSection();


                Swal.fire({
                    title: 'OTP Verified!',
                    text: data.message ||
                        'Your mobile number has been verified.',
                    icon: 'success',
                    confirmButtonColor: '#d92367'
                });


            } catch (error) {

                showError(
                    otpError,
                    error.message ||
                    'Invalid OTP. Please try again.'
                );


                verifyOtpBtn.disabled = false;

                verifyOtpBtn.textContent =
                    'Verify OTP';

            }

        }


        /* =========================================================
           VERIFY OTP BUTTON
        ========================================================== */

        verifyOtpBtn.addEventListener(
            'click',
            function() {

                verifyOtp();

            }
        );


        /* =========================================================
           RESET PASSWORD
        ========================================================== */

        async function resetPassword() {

            if (!validatePassword()) {
                return;
            }


            const password =
                passwordInput.value;

            const confirmation =
                passwordConfirmationInput.value;


            resetPasswordBtn.disabled = true;

            resetPasswordBtn.textContent =
                'Resetting Password...';


            try {

                const response =
                    await fetch(
                        RESET_PASSWORD_URL, {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': csrfToken
                            },

                            body: JSON.stringify({

                                mobile_number: currentMobile,

                                password: password,

                                password_confirmation: confirmation

                            })
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Unable to reset password.'
                    );
                }


                /*
                 * Password reset successfully
                 */

                Swal.fire({

                    title: 'Password Reset!',

                    text: data.message ||
                        'Your password has been reset successfully.',

                    icon: 'success',

                    confirmButtonText: 'Login',

                    confirmButtonColor: '#d92367'

                }).then(function() {

                    if (data.redirect) {

                        window.location.href =
                            data.redirect;

                    } else {

                        window.location.href =
                            '/login';

                    }

                });


            } catch (error) {

                showError(
                    passwordError,
                    error.message ||
                    'Unable to reset password.'
                );


                resetPasswordBtn.disabled = false;

                resetPasswordBtn.textContent =
                    'Reset Password';

            }

        }


        /* =========================================================
           RESET PASSWORD BUTTON
        ========================================================== */

        resetPasswordBtn.addEventListener(
            'click',
            function() {

                resetPassword();

            }
        );


        /* =========================================================
           MOBILE INPUT
           Allow numbers only
        ========================================================== */

        mobileInput.addEventListener(
            'input',
            function() {

                this.value =
                    this.value
                    .replace(/\D/g, '')
                    .substring(0, 10);

                showError(
                    mobileError,
                    ''
                );

            }
        );


        /* =========================================================
           OTP INPUT
           Allow numbers only
        ========================================================== */

        otpInput.addEventListener(
            'input',
            function() {

                this.value =
                    this.value
                    .replace(/\D/g, '')
                    .substring(0, 4);

                showError(
                    otpError,
                    ''
                );

            }
        );


        /* =========================================================
           PASSWORD INPUT
        ========================================================== */

        passwordInput.addEventListener(
            'input',
            function() {

                showError(
                    passwordError,
                    ''
                );

            }
        );


        passwordConfirmationInput.addEventListener(
            'input',
            function() {

                showError(
                    passwordError,
                    ''
                );

            }
        );


        /* =========================================================
           ENTER KEY - MOBILE
        ========================================================== */

        mobileInput.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Enter') {

                    event.preventDefault();

                    sendOtp();

                }

            }
        );


        /* =========================================================
           ENTER KEY - OTP
        ========================================================== */

        otpInput.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Enter') {

                    event.preventDefault();

                    verifyOtp();

                }

            }
        );


        /* =========================================================
           ENTER KEY - PASSWORD
        ========================================================== */

        passwordConfirmationInput.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Enter') {

                    event.preventDefault();

                    resetPassword();

                }

            }
        );


        /* =========================================================
           INITIAL STATE
        ========================================================== */

        showMobileSection();

    });
</script>
@endpush
