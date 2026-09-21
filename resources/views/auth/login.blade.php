@extends('layouts.public')
@section('title', 'Login or Register - ' . $siteName)
@section('description', 'Sign in to your ' . $siteName . ' account or create a profile to begin finding meaningful matches.')
@section('content')
<!-- MAIN -->

<div class="auth-layout">
  <div class="auth-left d-none d-md-block">
    <!-- LEFT PANEL — Hero / Branding -->
    <div class="login-hero-panel" aria-hidden="true">
      <div class="login-hero-overlay"></div>
      <img
        src="{{ asset('assets/images/login-hero-himrishtey.png') }}"
        alt=""
        class="login-hero-bg"
        loading="eager" />
      <div class="login-hero-content">
        <div class="login-hero-badge">
          <i data-lucide="shield-check" width="14" height="14"></i>
          {{ $siteHeroBadge }}
        </div>
        <h1 class="login-hero-title">{{ $siteHeroTitle }}</h1>
        <p class="login-hero-subtitle">{{ $siteTagline }}</p>
        <div class="login-hero-promise"><i data-lucide="heart-handshake" width="18" height="18"></i><span>Genuine profiles · Family-first support · Private by design</span></div>
      </div>
    </div>
  </div>
  <div class="auth-right">
    <!-- RIGHT PANEL — Login Form -->
    <div class="login-form-panel">
      <div class="login-form-wrap">

        <!-- Mobile Logo (visible only on small screens) -->
        <div class="login-mobile-brand">
          <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}">
        </div>

        <div class="login-form-header">
          <span class="login-form-kicker">Member access</span>
          <h2 class="login-form-title">Welcome back</h2>
          <p class="login-form-subtitle">Sign in to continue finding your match</p>
        </div>

        <!-- TAB SWITCHER: Login / Register -->
        <div class="login-tab-switcher" role="tablist" aria-label="Auth mode">
          <button class="login-tab active" role="tab" aria-selected="true" data-tab="login" id="tab-login" aria-controls="panel-login">Sign In</button>
          <button class="login-tab" role="tab" aria-selected="false" data-tab="register" id="tab-register" aria-controls="panel-register">Register</button>
          <span class="login-tab-indicator" aria-hidden="true"></span>
        </div>

        <!-- LOGIN PANEL -->
        <div class="login-tab-panel active" id="panel-login" role="tabpanel" aria-labelledby="tab-login">
          <form class="login-form" id="loginForm" novalidate>

            <div class="login-form-message" id="loginFormMessage" role="alert" aria-live="assertive" hidden></div>

            <!-- Mobile Number/ Email/ ProfileId -->
            <div class="form-group">
              <label class="form-label" for="loginField">
                <i data-lucide="smartphone" width="14" height="14"></i>
                Profile ID/ Email /Mobile Number
              </label>
              <div class="input-wrap">
                <!-- <span class="input-prefix">+91</span> -->
                <input
                  type="text"
                  id="loginField"
                  name="login"
                  class="form-input"
                  placeholder="Profile ID/Email/ Mobile number"
                  required />
              </div>
              <span class="form-error" id="loginFieldError" role="alert"></span>
            </div>

            <!-- Password -->
            <div class="form-group">
              <label class="form-label" for="loginPassword">
                <i data-lucide="lock" width="14" height="14"></i>
                Password
              </label>
              <div class="input-wrap">
                <input
                  type="password"
                  id="loginPassword"
                  name="password"
                  class="form-input"
                  placeholder="Enter your password"
                  autocomplete="current-password"
                  required />
                <button type="button" class="input-toggle-pass" aria-label="Show password" data-target="loginPassword">
                  <i data-lucide="eye" width="16" height="16"></i>
                </button>
              </div>
              <span class="form-error" id="loginPasswordError" role="alert"></span>
            </div>

            <!-- Captcha -->
            <div class="form-group">
              <label class="form-label" for="loginCaptcha">
                <i data-lucide="shield-check" width="14" height="14"></i>
                Security Check
              </label>

              <div class="captcha-container">
                <div class="captcha-box">
                  <span id="captchaQuestion">{{ $captcha }} =</span>
                </div>

                <!-- Optional Refresh Button
                  <button type="button" id="refreshCaptcha" class="captcha-refresh" title="Refresh Captcha">
                    <i data-lucide="refresh-cw" width="18" height="18"></i>
                  </button> -->
              </div>

              <div class="input-wrap">
                <input
                  type="number"
                  id="loginCaptcha"
                  name="captcha"
                  class="form-input"
                  placeholder="Enter your answer"
                  autocomplete="off"
                  required>
              </div>

              <span class="form-error" id="loginCaptchaError"></span>
            </div>

            <!-- Remember + Forgot -->
            <div class="login-form-row">
              <label class="checkbox-label">
                <input type="checkbox" id="rememberMe" name="remember" class="checkbox-input" />
                <span class="checkbox-custom" aria-hidden="true"></span>
                Remember me
              </label>
              <a href="{{ route('forgot.password') }}" class="forgot-link">Forgot password?</a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login public-cta public-cta-primary" id="loginSubmitBtn">
              <span class="btn-login-text">Sign In</span>
              <span class="btn-login-loader" aria-hidden="true"></span>
            </button>

            <!-- Divider -->
            <div class="login-divider">
              <span>or</span>
            </div>

            <button type="submit" class="btn-login public-cta public-cta-primary" id="loginOtpSubmitBtn" data-url="{{ route('login-with-otp') }}">
              <span class="btn-login-text">Login with OTP</span>
              <span class="btn-login-loader" aria-hidden="true"></span>
            </button>

            <div class="form-group" id="loginOtpGroup" style="display: none;">
              <label class="form-label" for="loginOtp">
                <i data-lucide="shield-check" width="14" height="14"></i>
                Enter OTP
              </label>

              <div class="input-wrap">
                <input
                  type="text"
                  id="loginOtp"
                  name="otp"
                  class="form-input"
                  placeholder="Enter 6-digit OTP"
                  inputmode="numeric"
                  maxlength="6"
                  autocomplete="one-time-code">
              </div>

              <span class="form-error" id="loginOtpError" role="alert"></span>

              <div style="margin-top: 8px;">
                <span id="loginOtpTimer"></span>
                <button
                  type="button"
                  id="resendLoginOtpBtn"
                  style="display:none;">
                  Resend OTP
                </button>
              </div>
            </div>

            {{-- Social sign-in is intentionally disabled until Google and Facebook OAuth are configured.
              <div class="login-divider">
                <span>or continue with</span>
              </div>

              <!-- Social Login -->
              <div class="social-login-row">
                <button type="button" class="social-btn google" aria-label="Continue with Google">
                  <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05" />
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                  </svg>
                  Google
                </button>
                <button type="button" class="social-btn facebook" aria-label="Continue with Facebook">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2" aria-hidden="true">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                  </svg>
                  Facebook
                </button>
              </div>
              --}}

            <p class="login-register-prompt">
              Don't have an account?
              <button type="button" class="switch-tab-btn" data-switch="register">Create one now</button>
            </p>

          </form>
        </div>

        <!-- REGISTER PANEL -->
        <div class="login-tab-panel" id="panel-register" role="tabpanel" aria-labelledby="tab-register" hidden>
          <form class="login-form" id="registerForm" novalidate>

            <!-- Profile For -->
            <div class="form-group">
              <label class="form-label">
                <i data-lucide="users" width="14" height="14"></i>
                Profile For
              </label>
              <div class="profile-for-grid">
                <label class="profile-for-option">
                  <input type="radio" name="profileFor" value="myself" checked />
                  <span class="pfo-inner">
                    <i data-lucide="user" width="16" height="16"></i>
                    Myself
                  </span>
                </label>
                <label class="profile-for-option">
                  <input type="radio" name="profileFor" value="son" />
                  <span class="pfo-inner">
                    <i data-lucide="user" width="16" height="16"></i>
                    My Son
                  </span>
                </label>
                <label class="profile-for-option">
                  <input type="radio" name="profileFor" value="daughter" />
                  <span class="pfo-inner">
                    <i data-lucide="user" width="16" height="16"></i>
                    My Daughter
                  </span>
                </label>
                <label class="profile-for-option">
                  <input type="radio" name="profileFor" value="brother" />
                  <span class="pfo-inner">
                    <i data-lucide="user" width="16" height="16"></i>
                    My Brother
                  </span>
                </label>
                <label class="profile-for-option">
                  <input type="radio" name="profileFor" value="sister" />
                  <span class="pfo-inner">
                    <i data-lucide="user" width="16" height="16"></i>
                    My Sister
                  </span>
                </label>
                <label class="profile-for-option">
                  <input type="radio" name="profileFor" value="friend" />
                  <span class="pfo-inner">
                    <i data-lucide="users" width="16" height="16"></i>
                    Friend
                  </span>
                </label>
              </div>
              <span class="form-error" id="regProfileError" role="alert"></span>
            </div>

            <!-- Name -->
            <div class="form-group">
              <label class="form-label" for="regName">
                <i data-lucide="user" width="14" height="14"></i>
                Full Name
              </label>
              <div class="input-wrap">
                <input
                  type="text"
                  id="regName"
                  name="name"
                  class="form-input"
                  placeholder="Enter full name"
                  autocomplete="name"
                  required />
              </div>
              <span class="form-error" id="regNameError" role="alert"></span>
            </div>

            <!-- email -->
            <div class="form-group">
              <label class="form-label" for="regEmail">
                <i data-lucide="mail" width="14" height="14"></i>
                Email
              </label>
              <div class="input-wrap">
                <input
                  type="email"
                  id="regEmail"
                  name="email"
                  class="form-input"
                  placeholder="Enter email"
                  autocomplete="email"
                  required />
              </div>
              <span class="form-error" id="regEmailError" role="alert"></span>
            </div>

            <!-- Mobile -->
            <div class="form-group">
              <label class="form-label" for="regMobile">
                <i data-lucide="smartphone" width="14" height="14"></i>
                Mobile Number
              </label>
              <div class="input-wrap">
                <span class="input-prefix">+91</span>
                <input
                  type="tel"
                  id="regMobile"
                  name="mobile"
                  class="form-input"
                  placeholder="10-digit mobile number"
                  maxlength="10"
                  inputmode="numeric"
                  pattern="[0-9]{10}"
                  autocomplete="tel"
                  required />
              </div>
              <span class="form-error" id="regMobileError" role="alert"></span>
            </div>

            <!-- Gender + DOB row -->
            <div class="form-row-2col">
              <div class="form-group">
                <label class="form-label" for="regGender">
                  <i data-lucide="user-2" width="14" height="14"></i>
                  Gender
                </label>
                <div class="input-wrap select-wrap">
                  <select id="regGender" name="gender" class="form-input form-select" required>
                    <option value="" disabled selected>Select gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                  <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                </div>
                <span class="form-error" id="regGenderError" role="alert"></span>
              </div>
              <div class="form-group">
                <label class="form-label" for="regDOB">
                  <i data-lucide="calendar" width="14" height="14"></i>
                  Date of Birth
                </label>
                <div class="input-wrap">
                  <input
                    type="date"
                    id="regDOB"
                    name="dob"
                    class="form-input"
                    required />
                </div>
                <span class="form-error" id="regDOBError" role="alert"></span>
              </div>
            </div>

            <!-- Password -->
            <div class="form-group">
              <label class="form-label" for="regPassword">
                <i data-lucide="lock" width="14" height="14"></i>
                Create Password
              </label>
              <div class="input-wrap">
                <input
                  type="password"
                  id="regPassword"
                  name="password"
                  class="form-input"
                  placeholder="Min. 6 characters"
                  autocomplete="new-password"
                  required
                  minlength="6" />
                <button type="button" class="input-toggle-pass" aria-label="Show password" data-target="regPassword">
                  <i data-lucide="eye" width="16" height="16"></i>
                </button>
              </div>
              <div class="password-strength-wrap" id="passwordStrengthWrap">
                <div class="password-strength-bar">
                  <span class="psb-fill" id="psb-fill"></span>
                </div>
                <span class="password-strength-label" id="passwordStrengthLabel"></span>
              </div>
              <span class="form-error" id="regPasswordError" role="alert"></span>
            </div>
            <!-- captcha -->
            <div class="form-group">
              <i data-lucide="shield-check" width="14" height="14"></i>
              <label class="form-label">Security Check</label>
              <div class="captcha-container">
                <div class="captcha-box">
                  <span id="registerCaptchaQuestion">{{ $captcha }} = </span>
                </div>
              </div>

              <input
                type="number"
                id="regCaptcha"
                class="form-input"
                placeholder="Enter your answer">

              <span class="form-error" id="regCaptchaError"></span>
            </div>

            <!-- Terms -->
            <div class="form-group">
              <div class="checkbox-label">
                <input type="checkbox" id="regTerms" name="terms" class="checkbox-input" required />
                <label for="regTerms" class="checkbox-custom" aria-label="Agree to the terms and conditions"></label>
                <span class="checkbox-copy">
                  <label for="regTerms">I agree to the </label><a href="{{ route('terms-and-conditions') }}" class="terms-link">Terms &amp; Conditions</a> and <a href="{{ route('privacy-policy') }}" class="terms-link">Privacy Policy</a>
                </span>
              </div>
              <span class="form-error" id="regTermsError" role="alert"></span>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login public-cta public-cta-primary" id="registerSubmitBtn">
              <span class="btn-login-text">Create Account</span>
              <span class="btn-login-loader" aria-hidden="true"></span>
            </button>

            <p class="login-register-prompt">
              Already have an account?
              <button type="button" class="switch-tab-btn" data-switch="login">Sign in here</button>
            </p>

          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection