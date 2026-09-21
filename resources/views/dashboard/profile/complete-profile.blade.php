@extends('layouts.public')
@section('title', 'Complete your profile - ' . $siteName)

@push('head')
<link href="{{ asset('assets/css/signup.css') }}" rel="stylesheet" />
@endpush
@section('content')
<!-- ================= MAIN ================= -->
<div class="su-main" id="main-content">
    <div class="container-xxl">
        <div class="su-card">

            <!-- ===== STEPPER (hidden on photo / success) ===== -->
            <div class="su-stepper-zone" id="suStepperZone">
                <ol class="su-stepper" id="suStepper" aria-label="Signup progress">
                    <li class="su-step is-active" data-step-index="1">
                        <span class="su-step-circle"><span class="su-step-num">1</span></span>
                        <span class="su-step-label">About You</span>
                    </li>
                    <li class="su-step" data-step-index="2">
                        <span class="su-step-circle"><span class="su-step-num">2</span></span>
                        <span class="su-step-label">Career</span>
                    </li>
                    <li class="su-step" data-step-index="3">
                        <span class="su-step-circle"><span class="su-step-num">3</span></span>
                        <span class="su-step-label">Community</span>
                    </li>
                </ol>
                <p class="su-step-caption" id="suStepCaption">Add some information about yourself</p>
            </div>

            <!-- ========================================================
             STEP 1 — ABOUT YOU
             (mirrors Flutter SignUpTwo: tob, height, country, state, city)
             ======================================================== -->
            <form class="su-panel is-active" id="stepForm1" data-panel="1" novalidate>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label class="form-label" for="tob">
                            <i data-lucide="clock" width="14" height="14"></i>
                            Time of Birth
                        </label>
                        <div class="input-wrap">
                            <input type="time" id="tob" name="tob" class="form-input" required />
                        </div>
                        <span class="form-error" id="tobError" role="alert"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="height">
                            <i data-lucide="ruler" width="14" height="14"></i>
                            Height
                        </label>
                        <div class="input-wrap select-wrap">
                            <select id="height" name="height" class="form-input form-select" required>
                                <option value="" disabled selected>Select height</option>
                                <option value="4'6">4'6" (137 cm)</option>
                                <option value="4'7">4'7" (140 cm)</option>
                                <option value="4'8">4'8" (142 cm)</option>
                                <option value="4'9">4'9" (145 cm)</option>
                                <option value="4'10">4'10" (147 cm)</option>
                                <option value="4'11">4'11" (150 cm)</option>
                                <option value="5'0">5'0" (152 cm)</option>
                                <option value="5'1">5'1" (155 cm)</option>
                                <option value="5'2">5'2" (157 cm)</option>
                                <option value="5'3">5'3" (160 cm)</option>
                                <option value="5'4">5'4" (163 cm)</option>
                                <option value="5'5">5'5" (165 cm)</option>
                                <option value="5'6">5'6" (168 cm)</option>
                                <option value="5'7">5'7" (170 cm)</option>
                                <option value="5'8">5'8" (173 cm)</option>
                                <option value="5'9">5'9" (175 cm)</option>
                                <option value="5'10">5'10" (178 cm)</option>
                                <option value="5'11">5'11" (180 cm)</option>
                                <option value="6'0">6'0" (183 cm)</option>
                                <option value="6'1">6'1" (185 cm)</option>
                                <option value="6'2">6'2" (188 cm)</option>
                                <option value="6'3">6'3" (191 cm)</option>
                                <option value="6'4">6'4" (193 cm)</option>
                                <option value="6'5">6'5" (196 cm)</option>
                            </select>
                            <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                        </div>
                        <span class="form-error" id="heightError" role="alert"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="country">
                        <i data-lucide="globe" width="14" height="14"></i>
                        Country
                    </label>
                    <div class="input-wrap select-wrap">
                        <select id="country" name="country" class="form-input form-select" required>
                            <option value="India">India</option>
                            <option value="United States">United States</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Canada">Canada</option>
                            <option value="Australia">Australia</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="Other">Other</option>
                        </select>
                        <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                    </div>
                    <span class="form-error" id="countryError" role="alert"></span>
                </div>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label class="form-label" for="state">
                            <i data-lucide="map" width="14" height="14"></i>
                            State
                        </label>
                        <div class="input-wrap">
                            <input
                                type="text"
                                id="state"
                                name="state"
                                class="form-input"
                                placeholder="Select or type your state"
                                list="stateList"
                                autocomplete="off"
                                required />
                            <datalist id="stateList"></datalist>
                        </div>
                        <span class="form-error" id="stateError" role="alert"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="city">
                            <i data-lucide="map-pin" width="14" height="14"></i>
                            City
                        </label>
                        <div class="input-wrap">
                            <input
                                type="text"
                                id="city"
                                name="city"
                                class="form-input"
                                placeholder="Select state first"
                                list="cityList"
                                autocomplete="off"
                                required
                                disabled />
                            <datalist id="cityList"></datalist>
                        </div>
                        <span class="form-error" id="cityError" role="alert"></span>
                    </div>
                </div>

                <div class="su-actions">
                    <a href="login.html" class="btn-su-secondary">
                        <i data-lucide="arrow-left" width="16" height="16"></i>
                        Back
                    </a>
                    <button type="submit" class="btn-login" id="step1SubmitBtn">
                        <span class="btn-login-text">Next <i data-lucide="arrow-right" width="16" height="16"></i></span>
                        <span class="btn-login-loader" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

            <!-- ========================================================
             STEP 2 — CAREER
             (mirrors Flutter SignUpThree: education, employed_in, occupation, income)
             ======================================================== -->
            <form class="su-panel" id="stepForm2" data-panel="2" novalidate>

                <div class="form-group">
                    <label class="form-label" for="education">
                        <i data-lucide="graduation-cap" width="14" height="14"></i>
                        Education
                    </label>
                    <div class="input-wrap select-wrap">
                        <select id="education" name="education" class="form-input form-select" required>
                            <option value="" disabled selected>Select education</option>
                            <option value="10">10th</option>
                            <option value="12">12th</option>
                            <option value="Diploma">Diploma</option>
                            <option value="B.A">B.A</option>
                            <option value="B.Sc.">B.Sc.</option>
                            <option value="B.Com">B.Com</option>
                            <option value="B.Tech / B.E.">B.Tech / B.E.</option>
                            <option value="BBA">BBA</option>
                            <option value="BCA">BCA</option>
                            <option value="M.A.">M.A.</option>
                            <option value="M.Sc.">M.Sc.</option>
                            <option value="M.Com">M.Com</option>
                            <option value="M.Tech.">M.Tech / M.E.</option>
                            <option value="MBA">MBA</option>
                            <option value="MCA">MCA</option>
                            <option value="Ph.D.">Doctorate (Ph.D.)</option>
                            <option value="other">Other</option>
                        </select>
                        <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                    </div>
                    <span class="form-error" id="educationError" role="alert"></span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="employedIn">
                        <i data-lucide="briefcase" width="14" height="14"></i>
                        Employed In
                    </label>
                    <div class="input-wrap select-wrap">
                        <select id="employedIn" name="employedIn" class="form-input form-select" required>
                            <option value="" disabled selected>Select employment type</option>
                            <option value="Government">Government / PSU</option>
                            <option value="Private">Private Sector</option>
                            <option value="Business">Business / Self Employed</option>
                            <option value="Defense">Defence Services</option>
                            <option value="Not Working">Not Working</option>
                        </select>
                        <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                    </div>
                    <span class="form-error" id="employedInError" role="alert"></span>
                </div>

                <div class="form-group" id="occupationGroup">
                    <label class="form-label" for="occupation">
                        <i data-lucide="id-card" width="14" height="14"></i>
                        Occupation
                    </label>
                    <div class="input-wrap select-wrap">
                        <!-- <input
                                type="text"
                                id="occupation"
                                name="occupation"
                                class="form-input"
                                placeholder="e.g. Software Engineer, Teacher, Doctor"
                                required /> -->
                        <select id="occupation" name="occupation" class="form-input form-select" required>
                            <option value="">Select</option>
                            <option value="Software Engineer">Software Engineer</option>
                            <option value="Doctor">Doctor</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Lawyer">Lawyer</option>
                            <option value="Banker">Banker</option>
                            <option value="Businessman">Businessman</option>
                            <option value="Farmer">Farmer</option>
                            <option value="Nurse">Nurse</option>
                            <option value="Architect">Architect</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <span class="form-error" id="occupationError" role="alert"></span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="income">
                        <i data-lucide="indian-rupee" width="14" height="14"></i>
                        Annual Income
                    </label>
                    <div class="input-wrap select-wrap">

                        <select id="income" name="income" class="form-input" required>
                            <option value="" disabled selected>Select annual income</option>
                            <option value="Below 1 LPA">Below 1 LPA</option>
                            <option value="1–2 LPA">1–2 LPA</option>
                            <option value="2–3 LPA">2–3 LPA</option>
                            <option value="3–5 LPA">3–5 LPA</option>
                            <option value="5–7 LPA">5–7 LPA</option>
                            <option value="7–10 LPA">7–10 LPA</option>
                            <option value="10–15 LPA">10–15 LPA</option>
                            <option value="15–20 LPA">15–20 LPA</option>
                            <option value="20–30 LPA">20–30 LPA</option>
                            <option value="30–50 LPA">30–50 LPA</option>
                            <option value="50 LPA+">50 LPA+</option>
                        </select>

                        <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                    </div>
                    <span class="form-error" id="incomeError" role="alert"></span>
                </div>

                <div class="su-actions">
                    <button type="button" class="btn-su-secondary" data-back>
                        <i data-lucide="arrow-left" width="16" height="16"></i>
                        Back
                    </button>
                    <button type="submit" class="btn-login" id="step2SubmitBtn">
                        <span class="btn-login-text">Next <i data-lucide="arrow-right" width="16" height="16"></i></span>
                        <span class="btn-login-loader" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

            <!-- ========================================================
             STEP 3 — COMMUNITY
             (mirrors Flutter SignUpFour: marital, tongue, religion, cast,
             manglik, horoscope, children)
             ======================================================== -->
            <form class="su-panel" id="stepForm3" data-panel="3" novalidate>

                <div class="form-group">
                    <label class="form-label" for="marital">
                        <i data-lucide="heart" width="14" height="14"></i>
                        Marital Status
                    </label>
                    <div class="input-wrap select-wrap">
                        <select id="marital" name="marital" class="form-input form-select" required>
                            <option value="" disabled selected>Select marital status</option>
                            <option value="Never Married">Never Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Awaiting Divorce">Awaiting Divorce</option>
                        </select>
                        <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                    </div>
                    <span class="form-error" id="maritalError" role="alert"></span>
                </div>

                <div class="form-group" id="childrenGroup" hidden>
                    <label class="form-label" for="children">
                        <i data-lucide="baby" width="14" height="14"></i>
                        Children
                    </label>
                    <div class="input-wrap select-wrap">
                        <select id="children" name="children" class="form-input form-select">
                            <option value="" disabled selected>Select number of children</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4+">4+</option>
                        </select>
                        <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                    </div>
                    <span class="form-error" id="childrenError" role="alert"></span>
                </div>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label class="form-label" for="tongue">
                            <i data-lucide="languages" width="14" height="14"></i>
                            Mother Tongue
                        </label>
                        <div class="input-wrap select-wrap">
                            <select id="tongue" name="tongue" class="form-input form-select" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Punjabi">Punjabi</option>
                                <option value="Pahari / Himachali">Pahari / Himachali</option>
                                <option value="Dogri">Dogri</option>
                                <option value="Gaddi">Gaddi</option>
                                <option value="Kinnauri">Kinnauri</option>
                                <option value="English">English</option>
                                <option value="other">Other</option>
                            </select>
                            <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                        </div>
                        <span class="form-error" id="tongueError" role="alert"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="religion">
                            <i data-lucide="landmark" width="14" height="14"></i>
                            Religion
                        </label>
                        <div class="input-wrap select-wrap">
                            <select id="religion" name="religion" class="form-input form-select" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Sikh">Sikh</option>
                                <option value="Muslim">Muslim</option>
                                <option value="Christian">Christian</option>
                                <option value="Buddhist">Buddhist</option>
                                <option value="Jain">Jain</option>
                                <option value="Other">Other</option>
                            </select>
                            <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                        </div>
                        <span class="form-error" id="religionError" role="alert"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="cast">
                        <i data-lucide="users" width="14" height="14"></i>
                        Cast
                    </label>
                    <div class="input-wrap select-wrap">
                        <select id="cast" name="cast" class="form-input form-select" required>
                            <option value="" disabled selected>Select cast</option>
                            <option value="Rajput">Rajput</option>
                            <option value="Brahmin">Brahmin</option>
                            <option value="Thakur">Thakur</option>
                            <option value="Kanet">Kanet</option>
                            <option value="Koli">Koli</option>
                            <option value="Rathi">Rathi</option>
                            <option value="Saini">Saini</option>
                            <option value="Khatri">Khatri</option>
                            <option value="Baniya">Baniya</option>
                            <option value="Other">Other</option>
                        </select>
                        <i data-lucide="chevron-down" width="14" height="14" class="select-icon" aria-hidden="true"></i>
                    </div>
                    <span class="form-error" id="castError" role="alert"></span>
                </div>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label class="form-label">
                            <i data-lucide="sparkle" width="14" height="14"></i>
                            Manglik
                        </label>
                        <div class="su-pill-grid" id="manglikGrid">
                            <label class="su-pill-option">
                                <input type="radio" name="manglik" value="Yes" />
                                <span class="su-pill-inner">Yes</span>
                            </label>
                            <label class="su-pill-option">
                                <input type="radio" name="manglik" value="No" />
                                <span class="su-pill-inner">No</span>
                            </label>
                        </div>
                        <span class="form-error" id="manglikError" role="alert"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i data-lucide="moon-star" width="14" height="14"></i>
                            Horoscope Needed
                        </label>
                        <div class="su-pill-grid" id="horoscopeGrid">
                            <label class="su-pill-option">
                                <input type="radio" name="horoscope" value="Yes" />
                                <span class="su-pill-inner">Yes</span>
                            </label>
                            <label class="su-pill-option">
                                <input type="radio" name="horoscope" value="No" />
                                <span class="su-pill-inner">No</span>
                            </label>
                        </div>
                        <span class="form-error" id="horoscopeError" role="alert"></span>
                    </div>
                </div>

                <div class="su-actions">
                    <button type="button" class="btn-su-secondary" data-back>
                        <i data-lucide="arrow-left" width="16" height="16"></i>
                        Back
                    </button>
                    <button type="submit" class="btn-login" id="step3SubmitBtn">
                        <span class="btn-login-text">Finish <i data-lucide="check" width="16" height="16"></i></span>
                        <span class="btn-login-loader" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

            <!-- ========================================================
             PHOTO UPLOAD (no step number — mirrors SignupUploadPic)
             ======================================================== -->
            <div class="su-panel su-panel-photo" id="panelPhoto" data-panel="photo">
                <h2 class="su-panel-title">Add a profile photo</h2>
                <p class="su-panel-subtitle">Profiles with a clear photo get up to 5&times; more responses. You can always add this later.</p>

                <label class="su-photo-circle" for="photoInput" id="photoCircle">
                    <img id="photoPreview" alt="" hidden />
                    <span class="su-photo-placeholder" id="photoPlaceholder">
                        <i data-lucide="camera" width="28" height="28"></i>
                        Upload Photo
                    </span>
                </label>
                <input type="file" id="photoInput" accept="image/*" hidden />
                <span class="form-error" id="photoError" role="alert"></span>

                <div class="su-actions su-actions-center">
                    <button type="button" class="btn-su-secondary" id="skipPhotoBtn">Skip for now</button>
                    <button type="button" class="btn-login" id="continuePhotoBtn" disabled>
                        <span class="btn-login-text">Continue <i data-lucide="arrow-right" width="16" height="16"></i></span>
                        <span class="btn-login-loader" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <!-- ========================================================
             SUCCESS (no step number — mirrors SignupSuccess)
             ======================================================== -->
            <div class="su-panel su-panel-success" id="panelSuccess" data-panel="success">
                <div class="su-success-icon">
                    <i data-lucide="check" width="32" height="32"></i>
                </div>
                <h2 class="su-panel-title">Welcome to {{ $siteName }}<span id="successName"></span>!</h2>
                <p class="su-panel-subtitle">Your profile has been created successfully. Our team will verify your details shortly — you can start exploring matches right away.</p>
                <a href="{{route('home')}}" class="btn-login su-success-cta">
                    <span class="btn-login-text">Go to Dashboard <i data-lucide="arrow-right" width="16" height="16"></i></span>
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
