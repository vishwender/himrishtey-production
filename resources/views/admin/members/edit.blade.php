@extends('admin.layout')

@section('title', 'Edit Member')
@section('page-title', 'Edit Member')

@section('content')

<div class="container-fluid members-page">

    {{-- Page Header --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 members-page-header">

        <div>

            <h1 class="h3 mb-1">
                Edit Member
            </h1>

            <div class="text-muted">

                {{ $member->full_name }}

                <span class="mx-2">|</span>

                Profile ID:
                <strong>{{ $member->profile_id }}</strong>

            </div>

        </div>


        <div>

            <a
                href="{{ route('admin.members.show', $member->id) }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Profile

            </a>

        </div>

    </div>


    {{-- Validation Errors --}}

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())

    <div class="alert alert-danger">

        <strong>Please correct the following:</strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    @include('admin.members.partials.photo-management')

    <form
        id="member-edit-form"
        action="{{ route('admin.members.update', $member->id) }}"
        method="POST"
        enctype="multipart/form-data"
        data-states-url="{{ route('admin.members.location.states', ['countryId' => '__ID__']) }}"
        data-cities-url="{{ route('admin.members.location.cities', ['stateId' => '__ID__']) }}">

        @csrf
        @method('PUT')


        {{-- =====================================================
            Basic Information
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Basic Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label" for="full_name">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="full_name" id="full_name"
                            class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name', $member->full_name) }}"
                            required aria-invalid="{{ $errors->has('full_name') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('full_name') ? 'full_name_error' : '' }}">
                        @error('full_name')
                        <div id="full_name_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $member->email) }}"
                            required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('email') ? 'email_error' : '' }}">
                        @error('email')
                        <div id="email_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="mobile_number">
                            Mobile Number
                        </label>

                        <input
                            type="text"
                            name="mobile_number" id="mobile_number"
                            class="form-control @error('mobile_number') is-invalid @enderror"
                            value="{{ old('mobile_number', $member->mobile_number) }}"
                            required aria-invalid="{{ $errors->has('mobile_number') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('mobile_number') ? 'mobile_number_error' : '' }}">
                        @error('mobile_number')
                        <div id="mobile_number_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="alternate_number">
                            Alternate Number
                        </label>

                        <input
                            type="text"
                            name="alternate_number" id="alternate_number"
                            class="form-control @error('alternate_number') is-invalid @enderror"
                            value="{{ old('alternate_number', $member->alternate_number) }}" aria-invalid="{{ $errors->has('alternate_number') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('alternate_number') ? 'alternate_number_error' : '' }}">
                        @error('alternate_number')
                        <div id="alternate_number_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="whatsapp_number">
                            WhatsApp Number
                        </label>

                        <input
                            type="text"
                            name="whatsapp_number" id="whatsapp_number"
                            class="form-control @error('whatsapp_number') is-invalid @enderror"
                            value="{{ old('whatsapp_number', $member->whatsapp_number) }}" aria-invalid="{{ $errors->has('whatsapp_number') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('whatsapp_number') ? 'whatsapp_number_error' : '' }}">
                        @error('whatsapp_number')
                        <div id="whatsapp_number_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="gender">
                            Gender
                        </label>

                        <input
                            type="text"
                            name="gender" id="gender"
                            class="form-control @error('gender') is-invalid @enderror"
                            value="{{ old('gender', $member->gender) }}" aria-invalid="{{ $errors->has('gender') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('gender') ? 'gender_error' : '' }}">
                        @error('gender')
                        <div id="gender_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="birth_date_time">
                            Birth Date and Time
                        </label>

                        <input
                            type="datetime-local"
                            id="birth_date_time"
                            name="birth_date_time"
                            class="form-control @error('birth_date_time') is-invalid @enderror"
                            value="{{ old('birth_date_time', $member->birth_date_time ? \Carbon\Carbon::parse($member->birth_date_time)->format('Y-m-d\TH:i') : '') }}"
                            max="{{ today()->subYearsNoOverflow(18)->endOfDay()->format('Y-m-d\TH:i') }}" aria-invalid="{{ $errors->has('birth_date_time') ? 'true' : 'false' }}" aria-describedby="birth_date_time_help{{ $errors->has('birth_date_time') ? ' birth_date_time_error' : '' }}">
                        @error('birth_date_time')
                        <div id="birth_date_time_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                        <div id="birth_date_time_help" class="form-text">The member must be at least 18 years old.</div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="height">
                            Height
                        </label>

                        <select name="height" id="height" class="form-select @error('height') is-invalid @enderror" aria-invalid="{{ $errors->has('height') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('height') ? 'height_error' : '' }}">
                            <option value="">Select Height</option>
                            @foreach($heights as $height)
                            <option value="{{ $height->height_value ?? $height->height }}" @selected(old('height', $member->height) == ($height->height_value ?? $height->height))>{{ \App\Support\HeightFormatter::format($height->height) }}</option>
                            @endforeach
                        </select>
                        @error('height')
                        <div id="height_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="blood_group">
                            Blood Group
                        </label>

                        <select name="blood_group" id="blood_group" class="form-select @error('blood_group') is-invalid @enderror" aria-invalid="{{ $errors->has('blood_group') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('blood_group') ? 'blood_group_error' : '' }}">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $blood)
                            <option value="{{ $blood }}" @selected(old('blood_group', $member->blood_group) == $blood)>{{ $blood }}</option>
                            @endforeach
                        </select>
                        @error('blood_group')
                        <div id="blood_group_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="marital_status">
                            Marital Status
                        </label>

                        <select name="marital_status" id="marital_status" class="form-select @error('marital_status') is-invalid @enderror" aria-invalid="{{ $errors->has('marital_status') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('marital_status') ? 'marital_status_error' : '' }}">
                            <option value="">Select Marital Status</option>
                            @foreach($maritalStatuses as $status)
                            <option value="{{ $status->marital_status }}" @selected(old('marital_status', $member->marital_status) == $status->marital_status)>{{ $status->marital_status }}</option>
                            @endforeach
                        </select>
                        @error('marital_status')
                        <div id="marital_status_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Religion & Community
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-heart me-2"></i>
                    Religion & Community
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label" for="religion">
                            Religion
                        </label>

                        <select name="religion" id="religion" class="form-select @error('religion') is-invalid @enderror" aria-invalid="{{ $errors->has('religion') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('religion') ? 'religion_error' : '' }}">
                            <option value="">Select Religion</option>
                            @foreach($religions as $religion)
                            <option value="{{ $religion->religion }}" @selected(old('religion', $member->religion) == $religion->religion)>{{ $religion->religion }}</option>
                            @endforeach
                        </select>
                        @error('religion')
                        <div id="religion_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="mother_tongue">
                            Mother Tongue
                        </label>

                        <select name="mother_tongue" id="mother_tongue" class="form-select @error('mother_tongue') is-invalid @enderror" aria-invalid="{{ $errors->has('mother_tongue') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('mother_tongue') ? 'mother_tongue_error' : '' }}">
                            <option value="">Select Mother Tongue</option>
                            @foreach($motherTongues as $motherTongue)
                            <option value="{{ $motherTongue->mother_tongue }}" @selected(old('mother_tongue', $member->mother_tongue) == $motherTongue->mother_tongue)>{{ $motherTongue->mother_tongue }}</option>
                            @endforeach
                        </select>
                        @error('mother_tongue')
                        <div id="mother_tongue_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="cast">
                            Caste
                        </label>

                        <select name="cast" id="cast" class="form-select @error('cast') is-invalid @enderror" aria-invalid="{{ $errors->has('cast') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('cast') ? 'cast_error' : '' }}">
                            <option value="">Select Caste</option>
                            @foreach($casts as $cast)
                            <option value="{{ $cast->cast }}" @selected(old('cast', $member->cast) == $cast->cast)>{{ $cast->cast }}</option>
                            @endforeach
                        </select>
                        @error('cast')
                        <div id="cast_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="sub_cast">
                            Sub Caste
                        </label>

                        <input
                            type="text"
                            name="sub_cast" id="sub_cast"
                            class="form-control @error('sub_cast') is-invalid @enderror"
                            value="{{ old('sub_cast', $member->sub_cast) }}" aria-invalid="{{ $errors->has('sub_cast') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('sub_cast') ? 'sub_cast_error' : '' }}">
                        @error('sub_cast')
                        <div id="sub_cast_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="gotra">
                            Gotra
                        </label>

                        <input
                            type="text"
                            name="gotra" id="gotra"
                            class="form-control @error('gotra') is-invalid @enderror"
                            value="{{ old('gotra', $member->gotra) }}" aria-invalid="{{ $errors->has('gotra') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('gotra') ? 'gotra_error' : '' }}">
                        @error('gotra')
                        <div id="gotra_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="manglik">
                            Manglik
                        </label>

                        <select name="manglik" id="manglik" class="form-select @error('manglik') is-invalid @enderror" aria-invalid="{{ $errors->has('manglik') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('manglik') ? 'manglik_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(['Yes', 'No'] as $value)
                            <option value="{{ $value }}" @selected(old('manglik', $member->manglik) == $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('manglik')
                        <div id="manglik_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Education
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-mortarboard me-2"></i>
                    Education
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label" for="education">
                            Education
                        </label>

                        <select name="education" id="education" class="form-select @error('education') is-invalid @enderror" aria-invalid="{{ $errors->has('education') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('education') ? 'education_error' : '' }}">
                            <option value="">Select Education</option>
                            @if(old('education', $member->education) && ! $educations->contains('education', old('education', $member->education)))
                                <option selected value="{{ old('education', $member->education) }}">{{ old('education', $member->education) }}</option>
                            @endif
                            @foreach($educations as $education)
                            <option value="{{ $education->education }}" @selected(old('education', $member->education) == $education->education)>{{ $education->education }}</option>
                            @endforeach
                        </select>
                        @error('education')
                        <div id="education_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="any_other_qualifications">
                            Other Qualifications
                        </label>

                        <input
                            type="text"
                            name="any_other_qualifications" id="any_other_qualifications"
                            class="form-control @error('any_other_qualifications') is-invalid @enderror"
                            value="{{ old('any_other_qualifications', $member->any_other_qualifications) }}" aria-invalid="{{ $errors->has('any_other_qualifications') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('any_other_qualifications') ? 'any_other_qualifications_error' : '' }}">
                        @error('any_other_qualifications')
                        <div id="any_other_qualifications_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-12">

                        <label class="form-label" for="about_my_education">
                            About Education
                        </label>

                        <textarea
                            name="about_my_education" id="about_my_education"
                            rows="3"
                            class="form-control @error('about_my_education') is-invalid @enderror" aria-invalid="{{ $errors->has('about_my_education') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('about_my_education') ? 'about_my_education_error' : '' }}">{{ old('about_my_education', $member->about_my_education) }}</textarea>
                        @error('about_my_education')
                        <div id="about_my_education_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Career
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-briefcase me-2"></i>
                    Career
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label" for="employed_in">
                            Employed In
                        </label>

                        <select name="employed_in" id="employed_in" class="form-select @error('employed_in') is-invalid @enderror" aria-invalid="{{ $errors->has('employed_in') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('employed_in') ? 'employed_in_error' : '' }}">
                            <option value="">Select Employer Type</option>
                            @foreach($employers as $employer)
                            <option value="{{ $employer->employer }}" @selected(old('employed_in', $member->employed_in) == $employer->employer)>{{ $employer->employer }}</option>
                            @endforeach
                        </select>
                        @error('employed_in')
                        <div id="employed_in_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="occupation">
                            Occupation
                        </label>

                        <select name="occupation" id="occupation" class="form-select @error('occupation') is-invalid @enderror" aria-invalid="{{ $errors->has('occupation') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('occupation') ? 'occupation_error' : '' }}">
                            <option value="">Select Occupation</option>
                            @foreach($occupations as $occupation)
                            <option value="{{ $occupation->occupation }}" @selected(old('occupation', $member->occupation) == $occupation->occupation)>{{ $occupation->occupation }}</option>
                            @endforeach
                        </select>
                        @error('occupation')
                        <div id="occupation_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="designation">
                            Designation
                        </label>

                        <input
                            type="text"
                            name="designation" id="designation"
                            class="form-control @error('designation') is-invalid @enderror"
                            value="{{ old('designation', $member->designation) }}" aria-invalid="{{ $errors->has('designation') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('designation') ? 'designation_error' : '' }}">
                        @error('designation')
                        <div id="designation_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="organization_name">
                            Organization
                        </label>

                        <input
                            type="text"
                            name="organization_name" id="organization_name"
                            class="form-control @error('organization_name') is-invalid @enderror"
                            value="{{ old('organization_name', $member->organization_name) }}" aria-invalid="{{ $errors->has('organization_name') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('organization_name') ? 'organization_name_error' : '' }}">
                        @error('organization_name')
                        <div id="organization_name_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="job_location">
                            Job Location
                        </label>

                        <input
                            type="text"
                            name="job_location" id="job_location"
                            class="form-control @error('job_location') is-invalid @enderror"
                            value="{{ old('job_location', $member->job_location) }}" aria-invalid="{{ $errors->has('job_location') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('job_location') ? 'job_location_error' : '' }}">
                        @error('job_location')
                        <div id="job_location_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="annual_income">
                            Annual Income
                        </label>

                        <select name="annual_income" id="annual_income" class="form-select @error('annual_income') is-invalid @enderror" aria-invalid="{{ $errors->has('annual_income') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('annual_income') ? 'annual_income_error' : '' }}">
                            @include('partials.annual-income-options', ['selected' => old('annual_income', $member->annual_income)])
                        </select>
                        @error('annual_income')
                        <div id="annual_income_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-12">

                        <label class="form-label" for="about_my_career">
                            About Career
                        </label>

                        <textarea
                            name="about_my_career" id="about_my_career"
                            rows="3"
                            class="form-control @error('about_my_career') is-invalid @enderror" aria-invalid="{{ $errors->has('about_my_career') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('about_my_career') ? 'about_my_career_error' : '' }}">{{ old('about_my_career', $member->about_my_career) }}</textarea>
                        @error('about_my_career')
                        <div id="about_my_career_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Location
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-geo-alt me-2"></i>
                    Location
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label" for="country_living_in">
                            Country
                        </label>

                        <select name="country_living_in" id="country_living_in" class="form-select @error('country_living_in') is-invalid @enderror" aria-invalid="{{ $errors->has('country_living_in') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('country_living_in') ? 'country_living_in_error' : '' }}">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->name }}" data-id="{{ $country->id }}" @selected(old('country_living_in', $member->country_living_in) == $country->name)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_living_in')
                        <div id="country_living_in_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="state_living_in">
                            State
                        </label>

                        <select name="state_living_in" id="state_living_in" class="form-select @error('state_living_in') is-invalid @enderror" data-current="{{ old('state_living_in', $member->state_living_in) }}" aria-invalid="{{ $errors->has('state_living_in') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('state_living_in') ? 'state_living_in_error' : '' }}">
                            @if(old('state_living_in', $member->state_living_in))
                                <option selected value="{{ old('state_living_in', $member->state_living_in) }}">{{ old('state_living_in', $member->state_living_in) }}</option>
                            @endif
                            <option value="">Select Country First</option>
                        </select>
                        @error('state_living_in')
                        <div id="state_living_in_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="city_living_in">
                            City
                        </label>

                        <select name="city_living_in" id="city_living_in" class="form-select @error('city_living_in') is-invalid @enderror" data-current="{{ old('city_living_in', $member->city_living_in) }}" aria-invalid="{{ $errors->has('city_living_in') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('city_living_in') ? 'city_living_in_error' : '' }}">
                            @if(old('city_living_in', $member->city_living_in))
                                <option selected value="{{ old('city_living_in', $member->city_living_in) }}">{{ old('city_living_in', $member->city_living_in) }}</option>
                            @endif
                            <option value="">Select State First</option>
                        </select>
                        @error('city_living_in')
                        <div id="city_living_in_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="native_place">
                            Native Place
                        </label>

                        <input
                            type="text"
                            name="native_place" id="native_place"
                            class="form-control @error('native_place') is-invalid @enderror"
                            value="{{ old('native_place', $member->native_place) }}" aria-invalid="{{ $errors->has('native_place') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('native_place') ? 'native_place_error' : '' }}">
                        @error('native_place')
                        <div id="native_place_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="address_living_in">
                            Address
                        </label>

                        <input
                            type="text"
                            name="address_living_in" id="address_living_in"
                            class="form-control @error('address_living_in') is-invalid @enderror"
                            value="{{ old('address_living_in', $member->address_living_in) }}" aria-invalid="{{ $errors->has('address_living_in') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('address_living_in') ? 'address_living_in_error' : '' }}">
                        @error('address_living_in')
                        <div id="address_living_in_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Family
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-house-heart me-2"></i>
                    Family
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label" for="family_type">
                            Family Type
                        </label>

                        <select
                            name="family_type"
                            id="family_type"
                            class="form-select @error('family_type') is-invalid @enderror" aria-invalid="{{ $errors->has('family_type') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('family_type') ? 'family_type_error' : '' }}">

                            <option value="">
                                Select
                            </option>

                            <option
                                value="Joint"
                                @selected(old('family_type', $member->family_type)=='Joint' )>

                                Joint

                            </option>

                            <option
                                value="Nuclear"
                                @selected(old('family_type', $member->family_type)=='Nuclear' )>

                                Nuclear

                            </option>

                        </select>
                        @error('family_type')
                        <div id="family_type_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="family_status">
                            Family Status
                        </label>

                        <select name="family_status" id="family_status" class="form-select @error('family_status') is-invalid @enderror" aria-invalid="{{ $errors->has('family_status') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('family_status') ? 'family_status_error' : '' }}">
                            <option value="">Select Family Status</option>
                            @foreach($familyStatuses as $status)
                            <option value="{{ $status->value }}" @selected(old('family_status', $member->family_status) == $status->value)>{{ $status->value }}</option>
                            @endforeach
                        </select>
                        @error('family_status')
                        <div id="family_status_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>



                    <div class="col-md-6">

                        <label class="form-label" for="father_name">
                            Father Name
                        </label>

                        <input
                            type="text"
                            name="father_name" id="father_name"
                            class="form-control @error('father_name') is-invalid @enderror"
                            value="{{ old('father_name', $member->father_name) }}" aria-invalid="{{ $errors->has('father_name') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('father_name') ? 'father_name_error' : '' }}">
                        @error('father_name')
                        <div id="father_name_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="father_occupation">
                            Father Occupation
                        </label>

                        <input
                            type="text"
                            name="father_occupation" id="father_occupation"
                            class="form-control @error('father_occupation') is-invalid @enderror"
                            value="{{ old('father_occupation', $member->father_occupation) }}" aria-invalid="{{ $errors->has('father_occupation') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('father_occupation') ? 'father_occupation_error' : '' }}">
                        @error('father_occupation')
                        <div id="father_occupation_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="mother_name">
                            Mother Name
                        </label>

                        <input
                            type="text"
                            name="mother_name" id="mother_name"
                            class="form-control @error('mother_name') is-invalid @enderror"
                            value="{{ old('mother_name', $member->mother_name) }}" aria-invalid="{{ $errors->has('mother_name') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('mother_name') ? 'mother_name_error' : '' }}">
                        @error('mother_name')
                        <div id="mother_name_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="mother_occupation">
                            Mother Occupation
                        </label>

                        <input
                            type="text"
                            name="mother_occupation" id="mother_occupation"
                            class="form-control @error('mother_occupation') is-invalid @enderror"
                            value="{{ old('mother_occupation', $member->mother_occupation) }}" aria-invalid="{{ $errors->has('mother_occupation') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('mother_occupation') ? 'mother_occupation_error' : '' }}">
                        @error('mother_occupation')
                        <div id="mother_occupation_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label" for="no_of_brothers">
                            Brother
                        </label>

                        <select name="no_of_brothers" id="no_of_brothers" class="form-select @error('no_of_brothers') is-invalid @enderror" aria-invalid="{{ $errors->has('no_of_brothers') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('no_of_brothers') ? 'no_of_brothers_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(range(0, 5) as $number)
                            <option value="{{ $number }}" @selected(old('no_of_brothers', $member->no_of_brothers) == $number)>{{ $number }}</option>
                            @endforeach
                        </select>
                        @error('no_of_brothers')
                        <div id="no_of_brothers_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label" for="married_brothers">
                            Married Brother
                        </label>

                        <select name="married_brothers" id="married_brothers" class="form-select @error('married_brothers') is-invalid @enderror" aria-invalid="{{ $errors->has('married_brothers') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('married_brothers') ? 'married_brothers_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(range(0, 5) as $number)
                            <option value="{{ $number }}" @selected(old('married_brothers', $member->married_brothers) == $number)>{{ $number }}</option>
                            @endforeach
                        </select>
                        @error('married_brothers')
                        <div id="married_brothers_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                        <input type="hidden" id="married_brothers_zero" value="0" disabled>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label" for="no_of_sisters">
                            Sister
                        </label>

                        <select name="no_of_sisters" id="no_of_sisters" class="form-select @error('no_of_sisters') is-invalid @enderror" aria-invalid="{{ $errors->has('no_of_sisters') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('no_of_sisters') ? 'no_of_sisters_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(range(0, 5) as $number)
                            <option value="{{ $number }}" @selected(old('no_of_sisters', $member->no_of_sisters) == $number)>{{ $number }}</option>
                            @endforeach
                        </select>
                        @error('no_of_sisters')
                        <div id="no_of_sisters_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label" for="married_sisters">
                            Married Sister
                        </label>

                        <select name="married_sisters" id="married_sisters" class="form-select @error('married_sisters') is-invalid @enderror" aria-invalid="{{ $errors->has('married_sisters') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('married_sisters') ? 'married_sisters_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(range(0, 5) as $number)
                            <option value="{{ $number }}" @selected(old('married_sisters', $member->married_sisters) == $number)>{{ $number }}</option>
                            @endforeach
                        </select>
                        @error('married_sisters')
                        <div id="married_sisters_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                        <input type="hidden" id="married_sisters_zero" value="0" disabled>

                    </div>


                    <div class="col-12">

                        <label class="form-label" for="about_family">
                            About Family
                        </label>

                        <textarea
                            name="about_family" id="about_family"
                            rows="3"
                            class="form-control @error('about_family') is-invalid @enderror" aria-invalid="{{ $errors->has('about_family') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('about_family') ? 'about_family_error' : '' }}">{{ old('about_family', $member->about_family) }}</textarea>
                        @error('about_family')
                        <div id="about_family_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Lifestyle
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-person-lines-fill me-2"></i>
                    Lifestyle
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label" for="diet">
                            Diet
                        </label>

                        <select name="diet" id="diet" class="form-select @error('diet') is-invalid @enderror" aria-invalid="{{ $errors->has('diet') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('diet') ? 'diet_error' : '' }}">
                            <option value="">Select Diet</option>
                            @foreach(['Veg', 'Veg & Non Veg', 'Non Veg'] as $value)
                            <option value="{{ $value }}" @selected(old('diet', $member->diet) == $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('diet')
                        <div id="diet_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="is_drinking">
                            Drinking
                        </label>

                        <select name="is_drinking" id="is_drinking" class="form-select @error('is_drinking') is-invalid @enderror" aria-invalid="{{ $errors->has('is_drinking') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('is_drinking') ? 'is_drinking_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(['Yes', 'No', 'Occasionally'] as $value)
                            <option value="{{ $value }}" @selected(old('is_drinking', $member->is_drinking) == $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('is_drinking')
                        <div id="is_drinking_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="is_smoking">
                            Smoking
                        </label>

                        <select name="is_smoking" id="is_smoking" class="form-select @error('is_smoking') is-invalid @enderror" aria-invalid="{{ $errors->has('is_smoking') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('is_smoking') ? 'is_smoking_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(['Yes', 'No', 'Occasionally'] as $value)
                            <option value="{{ $value }}" @selected(old('is_smoking', $member->is_smoking) == $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('is_smoking')
                        <div id="is_smoking_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="any_disability">
                            Disability
                        </label>

                        @php
                        $disabilitySelection = old('any_disability', \App\Support\DisabilityValue::selection($member->any_disability));
                        $disabilityDescription = old('health_info', \App\Support\DisabilityValue::description($member->any_disability, $member->health_info ?? null));
                        @endphp
                        <select name="any_disability" id="any_disability" class="form-select @error('any_disability') is-invalid @enderror" aria-invalid="{{ $errors->has('any_disability') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('any_disability') ? 'any_disability_error' : '' }}">
                            <option value="">Select</option>
                            @foreach(['Yes', 'No'] as $value)
                            <option value="{{ $value }}" @selected($disabilitySelection == $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('any_disability')
                        <div id="any_disability_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6 {{ $disabilitySelection === 'Yes' ? '' : 'd-none' }}" id="disability_description_group">
                        <label for="health_info" class="form-label">Describe Disability</label>
                        <input type="text" name="health_info" id="health_info" class="form-control @error('health_info') is-invalid @enderror"
                            value="{{ $disabilityDescription }}" maxlength="255" aria-invalid="{{ $errors->has('health_info') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('health_info') ? 'health_info_error' : '' }}">
                        @error('health_info')
                        <div id="health_info_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-12">

                        <label class="form-label" for="about_me">
                            About Me
                        </label>

                        <textarea
                            name="about_me" id="about_me"
                            rows="4"
                            class="form-control @error('about_me') is-invalid @enderror" aria-invalid="{{ $errors->has('about_me') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('about_me') ? 'about_me_error' : '' }}">{{ old('about_me', $member->about_me) }}</textarea>
                        @error('about_me')
                        <div id="about_me_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Admin / Membership
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4 member-section">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Admin & Membership
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label" for="plan_id">
                            Membership Plan
                        </label>

                        <select
                            name="plan_id" id="plan_id"
                            class="form-select @error('plan_id') is-invalid @enderror" aria-invalid="{{ $errors->has('plan_id') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('plan_id') ? 'plan_id_error' : '' }}">

                            <option value="">
                                No Plan
                            </option>

                            @foreach($plans as $plan)

                            <option
                                value="{{ $plan->id }}"
                                {{ (string) old('plan_id', $member->plan_id) === (string) $plan->id ? 'selected' : '' }}>

                                {{ $plan->plan_name }}

                                @if($plan->membership_type)
                                — {{ $plan->membership_type }}
                                @endif

                            </option>

                            @endforeach

                        </select>
                        @error('plan_id')
                        <div id="plan_id_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="profile_hide">
                            Profile Visibility
                        </label>

                        <select
                            name="profile_hide" id="profile_hide"
                            class="form-select @error('profile_hide') is-invalid @enderror" aria-invalid="{{ $errors->has('profile_hide') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('profile_hide') ? 'profile_hide_error' : '' }}">

                            <option
                                value="No"
                                {{ old('profile_hide', $member->profile_hide) === 'No' ? 'selected' : '' }}>
                                Visible
                            </option>

                            <option
                                value="Yes"
                                {{ old('profile_hide', $member->profile_hide) === 'Yes' ? 'selected' : '' }}>
                                Hidden
                            </option>

                        </select>
                        @error('profile_hide')
                        <div id="profile_hide_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label" for="relationship_manager">
                            Relationship Manager
                        </label>

                        @if(app(\App\Services\RelationshipManagerAccess::class)->isRestricted())

                        <input
                            type="text" id="relationship_manager"
                            class="form-control"
                            value="{{ $member->relationship_manager }}"
                            disabled>

                        @else

                        <select
                            name="relationship_manager" id="relationship_manager"
                            class="form-select @error('relationship_manager') is-invalid @enderror" aria-invalid="{{ $errors->has('relationship_manager') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('relationship_manager') ? 'relationship_manager_error' : '' }}">

                            <option value="">
                                — No Relationship Manager —
                            </option>

                            @foreach($relationshipManagers as $manager)

                            <option
                                value="{{ $manager->name }}"
                                {{ old('relationship_manager', $member->relationship_manager) === $manager->name ? 'selected' : '' }}>
                                {{ $manager->name }} ({{ $manager->profile_id }} · {{ $manager->email }})
                            </option>

                            @endforeach

                        </select>
                        @error('relationship_manager')
                        <div id="relationship_manager_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                        @endif

                    </div>


                    <div class="col-12">

                        <label class="form-label" for="remarks">
                            Admin Remarks
                        </label>

                        <textarea
                            name="remarks" id="remarks"
                            rows="3"
                            class="form-control @error('remarks') is-invalid @enderror" aria-invalid="{{ $errors->has('remarks') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('remarks') ? 'remarks_error' : '' }}">{{ old('remarks', $member->remarks) }}</textarea>
                        @error('remarks')
                        <div id="remarks_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>

        {{-- =========================================================
    PARTNER PREFERENCES
========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <div class="d-flex align-items-center">

                    <div
                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                        style="width:42px;height:42px;">

                        <i class="bi bi-heart fs-5"></i>

                    </div>

                    <div>
                        <h5 class="mb-1">
                            Partner Preferences
                        </h5>

                        <small class="text-muted">
                            Preferences for the member's desired partner
                        </small>
                    </div>

                </div>

            </div>


            <div class="card-body px-4 pb-4">


                {{-- =====================================================
            BASIC PREFERENCES
        ====================================================== --}}

                <h6 class="text-primary border-bottom pb-2 mb-3">
                    <i class="bi bi-person-heart me-2"></i>
                    Basic Preferences
                </h6>


                <div class="row g-3">


                    {{-- Looking For --}}
                    <div class="col-md-6">

                        <label for="looking_for" class="form-label">
                            Looking For
                        </label>

                        <select name="looking_for" id="looking_for" class="form-select @error('looking_for') is-invalid @enderror" aria-invalid="{{ $errors->has('looking_for') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('looking_for') ? 'looking_for_error' : '' }}">
                            <option value="">Select Marital Status</option>
                            @foreach($maritalStatuses as $status)
                            <option value="{{ $status->marital_status }}" @selected(old('looking_for', $member->looking_for) == $status->marital_status)>{{ $status->marital_status }}</option>
                            @endforeach
                        </select>
                        @error('looking_for')
                        <div id="looking_for_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Partner Age From --}}
                    <div class="col-md-3">

                        <label for="partner_age_from" class="form-label">
                            Age From
                        </label>

                        <input
                            type="number" min="18"
                            class="form-control @error('partner_age_from') is-invalid @enderror"
                            id="partner_age_from"
                            name="partner_age_from"
                            value="{{ old('partner_age_from', $member->partner_age_from) }}"
                            min="18"
                            max="100"
                            placeholder="Example: 25" aria-invalid="{{ $errors->has('partner_age_from') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_age_from') ? 'partner_age_from_error' : '' }}">
                        @error('partner_age_from')
                        <div id="partner_age_from_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Partner Age To --}}
                    <div class="col-md-3">

                        <label for="partner_age_to" class="form-label">
                            Age To
                        </label>

                        <input
                            type="number" min="18"
                            class="form-control @error('partner_age_to') is-invalid @enderror"
                            id="partner_age_to"
                            name="partner_age_to"
                            value="{{ old('partner_age_to', $member->partner_age_to) }}"
                            min="18"
                            max="100"
                            placeholder="Example: 30" aria-invalid="{{ $errors->has('partner_age_to') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_age_to') ? 'partner_age_to_error' : '' }}">
                        @error('partner_age_to')
                        <div id="partner_age_to_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Partner Height From --}}
                    <div class="col-md-6">

                        <label class="form-label" for="partner_height_from">
                            Height From
                        </label>

                        <select name="partner_height_from" id="partner_height_from" class="form-select @error('partner_height_from') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_height_from') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_height_from') ? 'partner_height_from_error' : '' }}">
                            <option value="">Select Height</option>
                            @foreach($heights as $height)
                            <option value="{{ $height->height_value ?? $height->height }}" @selected(old('partner_height_from', $member->partner_height_from) == ($height->height_value ?? $height->height))>{{ \App\Support\HeightFormatter::format($height->height) }}</option>
                            @endforeach
                        </select>
                        @error('partner_height_from')
                        <div id="partner_height_from_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label" for="partner_height_to">
                            Height To
                        </label>

                        <select name="partner_height_to" id="partner_height_to" class="form-select @error('partner_height_to') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_height_to') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_height_to') ? 'partner_height_to_error' : '' }}">
                            <option value="">Select Height</option>
                            @foreach($heights as $height)
                            <option value="{{ $height->height_value ?? $height->height }}" @selected(old('partner_height_to', $member->partner_height_to) == ($height->height_value ?? $height->height))>{{ \App\Support\HeightFormatter::format($height->height) }}</option>
                            @endforeach
                        </select>
                        @error('partner_height_to')
                        <div id="partner_height_to_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
            LOCATION
        ====================================================== --}}

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-5">

                    <i class="bi bi-geo-alt me-2"></i>
                    Location Preference

                </h6>


                <div class="row g-3">


                    {{-- Country --}}
                    <div class="col-md-4">

                        <label for="partner_country" class="form-label">
                            Country
                        </label>

                        <select name="partner_country" id="partner_country" class="form-select @error('partner_country') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_country') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_country') ? 'partner_country_error' : '' }}">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->name }}" data-id="{{ $country->id }}" @selected(old('partner_country', $member->partner_country) == $country->name)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('partner_country')
                        <div id="partner_country_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- State --}}
                    <div class="col-md-4">

                        <label for="partner_state" class="form-label">
                            State
                        </label>

                        <select name="partner_state" id="partner_state" class="form-select @error('partner_state') is-invalid @enderror" data-current="{{ old('partner_state', $member->partner_state) }}" aria-invalid="{{ $errors->has('partner_state') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_state') ? 'partner_state_error' : '' }}">
                            @if(old('partner_state', $member->partner_state))
                                <option selected value="{{ old('partner_state', $member->partner_state) }}">{{ old('partner_state', $member->partner_state) }}</option>
                            @endif
                            <option value="">Select Country First</option>
                        </select>
                        @error('partner_state')
                        <div id="partner_state_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- City --}}
                    <div class="col-md-4">

                        <label for="partner_city" class="form-label">
                            City
                        </label>

                        <select name="partner_city" id="partner_city" class="form-select @error('partner_city') is-invalid @enderror" data-current="{{ old('partner_city', $member->partner_city) }}" aria-invalid="{{ $errors->has('partner_city') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_city') ? 'partner_city_error' : '' }}">
                            @if(old('partner_city', $member->partner_city))
                                <option selected value="{{ old('partner_city', $member->partner_city) }}">{{ old('partner_city', $member->partner_city) }}</option>
                            @endif
                            <option value="">Select State First</option>
                        </select>
                        @error('partner_city')
                        <div id="partner_city_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
            RELIGION & COMMUNITY
        ====================================================== --}}

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-5">

                    <i class="bi bi-stars me-2"></i>
                    Religion & Community

                </h6>


                <div class="row g-3">


                    {{-- Religion --}}
                    <div class="col-md-6">

                        <label for="partner_religion" class="form-label">
                            Religion
                        </label>

                        <select name="partner_religion" id="partner_religion" class="form-select @error('partner_religion') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_religion') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_religion') ? 'partner_religion_error' : '' }}">
                            <option value="">Select Religion</option>
                            @foreach($religions as $religion)
                            <option value="{{ $religion->religion }}" @selected(old('partner_religion', $member->partner_religion) == $religion->religion)>{{ $religion->religion }}</option>
                            @endforeach
                        </select>
                        @error('partner_religion')
                        <div id="partner_religion_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Caste --}}
                    <div class="col-md-6">

                        <label for="partner_cast" class="form-label">
                            Caste
                        </label>

                        <select name="partner_cast" id="partner_cast" class="form-select @error('partner_cast') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_cast') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_cast') ? 'partner_cast_error' : '' }}">
                            <option value="">Select Caste</option>
                            @foreach($casts as $cast)
                            <option value="{{ $cast->cast }}" @selected(old('partner_cast', $member->partner_cast) == $cast->cast)>{{ $cast->cast }}</option>
                            @endforeach
                        </select>
                        @error('partner_cast')
                        <div id="partner_cast_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Mother Tongue --}}
                    <div class="col-md-6">

                        <label for="partner_mothertongue" class="form-label">
                            Mother Tongue
                        </label>

                        <select name="partner_mothertongue" id="partner_mothertongue" class="form-select @error('partner_mothertongue') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_mothertongue') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_mothertongue') ? 'partner_mothertongue_error' : '' }}">
                            <option value="">Select Mother Tongue</option>
                            @if(old('partner_mothertongue', $member->partner_mothertongue) && ! $motherTongues->contains('mother_tongue', old('partner_mothertongue', $member->partner_mothertongue)))
                                <option selected value="{{ old('partner_mothertongue', $member->partner_mothertongue) }}">{{ old('partner_mothertongue', $member->partner_mothertongue) }}</option>
                            @endif
                            @foreach($motherTongues as $motherTongue)
                            <option value="{{ $motherTongue->mother_tongue }}" @selected(old('partner_mothertongue', $member->partner_mothertongue) == $motherTongue->mother_tongue)>{{ $motherTongue->mother_tongue }}</option>
                            @endforeach
                        </select>
                        @error('partner_mothertongue')
                        <div id="partner_mothertongue_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Manglik --}}
                    <div class="col-md-6">

                        <label for="is_partner_manglik" class="form-label">
                            Manglik Preference
                        </label>

                        <select
                            class="form-select @error('is_partner_manglik') is-invalid @enderror"
                            id="is_partner_manglik"
                            name="is_partner_manglik" aria-invalid="{{ $errors->has('is_partner_manglik') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('is_partner_manglik') ? 'is_partner_manglik_error' : '' }}">

                            <option value="">
                                Select Preference
                            </option>

                            <option
                                value="Yes"
                                {{ old('is_partner_manglik', $member->is_partner_manglik) == 'Yes' ? 'selected' : '' }}>
                                Yes
                            </option>

                            <option
                                value="No"
                                {{ old('is_partner_manglik', $member->is_partner_manglik) == 'No' ? 'selected' : '' }}>
                                No
                            </option>

                        </select>
                        @error('is_partner_manglik')
                        <div id="is_partner_manglik_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
            EDUCATION & CAREER
        ====================================================== --}}

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-5">

                    <i class="bi bi-mortarboard me-2"></i>
                    Education & Career

                </h6>


                <div class="row g-3">


                    {{-- Education --}}
                    <div class="col-md-6">

                        <label for="partner_education" class="form-label">
                            Education
                        </label>

                        <select name="partner_education" id="partner_education" class="form-select @error('partner_education') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_education') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_education') ? 'partner_education_error' : '' }}">
                            <option value="">Select Education</option>
                            @if(old('partner_education', $member->partner_education) && ! $educations->contains('education', old('partner_education', $member->partner_education)))
                                <option selected value="{{ old('partner_education', $member->partner_education) }}">{{ old('partner_education', $member->partner_education) }}</option>
                            @endif
                            @foreach($educations as $education)
                            <option value="{{ $education->education }}" @selected(old('partner_education', $member->partner_education) == $education->education)>{{ $education->education }}</option>
                            @endforeach
                        </select>
                        @error('partner_education')
                        <div id="partner_education_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Occupation --}}
                    <div class="col-md-6">

                        <label for="partner_occupation" class="form-label">
                            Occupation
                        </label>

                        <select name="partner_occupation" id="partner_occupation" class="form-select @error('partner_occupation') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_occupation') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_occupation') ? 'partner_occupation_error' : '' }}">
                            <option value="">Select Occupation</option>
                            @foreach($occupations as $occupation)
                            <option value="{{ $occupation->occupation }}" @selected(old('partner_occupation', $member->partner_occupation) == $occupation->occupation)>{{ $occupation->occupation }}</option>
                            @endforeach
                        </select>
                        @error('partner_occupation')
                        <div id="partner_occupation_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Annual Income From --}}
                    <div class="col-md-6">

                        <label for="partner_annual_income_from" class="form-label">
                            Annual Income From
                        </label>

                        <select name="partner_annual_income_from" id="partner_annual_income_from" class="form-select @error('partner_annual_income_from') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_annual_income_from') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_annual_income_from') ? 'partner_annual_income_from_error' : '' }}">
                            @include('partials.annual-income-options', ['selected' => old('partner_annual_income_from', $member->partner_annual_income_from)])
                        </select>
                        @error('partner_annual_income_from')
                        <div id="partner_annual_income_from_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Annual Income To --}}
                    <div class="col-md-6">

                        <label for="partner_annual_income_to" class="form-label">
                            Annual Income To
                        </label>

                        <select name="partner_annual_income_to" id="partner_annual_income_to" class="form-select @error('partner_annual_income_to') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_annual_income_to') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_annual_income_to') ? 'partner_annual_income_to_error' : '' }}">
                            @include('partials.annual-income-options', ['selected' => old('partner_annual_income_to', $member->partner_annual_income_to)])
                        </select>
                        @error('partner_annual_income_to')
                        <div id="partner_annual_income_to_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
            LIFESTYLE
        ====================================================== --}}

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-5">

                    <i class="bi bi-cup-hot me-2"></i>
                    Lifestyle Preferences

                </h6>


                <div class="row g-3">


                    {{-- Diet --}}
                    <div class="col-md-4">

                        <label for="partner_diet" class="form-label">
                            Diet
                        </label>

                        <select name="partner_diet" id="partner_diet" class="form-select @error('partner_diet') is-invalid @enderror" aria-invalid="{{ $errors->has('partner_diet') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('partner_diet') ? 'partner_diet_error' : '' }}">
                            <option value="">Select Diet</option>
                            @foreach(['Veg', 'Veg & Non Veg', 'Non Veg'] as $value)
                            <option value="{{ $value }}" @selected(old('partner_diet', $member->partner_diet) == $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('partner_diet')
                        <div id="partner_diet_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Smoking --}}
                    <div class="col-md-4">

                        <label for="is_partner_smoking" class="form-label">
                            Smoking
                        </label>

                        <select
                            class="form-select @error('is_partner_smoking') is-invalid @enderror"
                            id="is_partner_smoking"
                            name="is_partner_smoking" aria-invalid="{{ $errors->has('is_partner_smoking') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('is_partner_smoking') ? 'is_partner_smoking_error' : '' }}">

                            <option value="">
                                Select Preference
                            </option>

                            @foreach(['Yes', 'No', 'Occasionally'] as $value)
                            <option value="{{ $value }}" @selected(old('is_partner_smoking', $member->is_partner_smoking) == $value)>{{ $value }}</option>
                            @endforeach

                        </select>
                        @error('is_partner_smoking')
                        <div id="is_partner_smoking_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Drinking --}}
                    <div class="col-md-4">

                        <label for="is_partner_drinking" class="form-label">
                            Drinking
                        </label>

                        <select
                            class="form-select @error('is_partner_drinking') is-invalid @enderror"
                            id="is_partner_drinking"
                            name="is_partner_drinking" aria-invalid="{{ $errors->has('is_partner_drinking') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('is_partner_drinking') ? 'is_partner_drinking_error' : '' }}">

                            <option value="">
                                Select Preference
                            </option>

                            @foreach(['Yes', 'No', 'Occasionally'] as $value)
                            <option value="{{ $value }}" @selected(old('is_partner_drinking', $member->is_partner_drinking) == $value)>{{ $value }}</option>
                            @endforeach

                        </select>
                        @error('is_partner_drinking')
                        <div id="is_partner_drinking_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
            ABOUT PARTNER
        ====================================================== --}}

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-5">

                    <i class="bi bi-chat-heart me-2"></i>
                    About My Partner

                </h6>


                <div class="row">

                    <div class="col-12">

                        <label for="about_my_partner" class="form-label">
                            Partner Description
                        </label>

                        <textarea
                            class="form-control @error('about_my_partner') is-invalid @enderror"
                            id="about_my_partner"
                            name="about_my_partner"
                            rows="5"
                            placeholder="Describe the kind of partner you are looking for..." aria-invalid="{{ $errors->has('about_my_partner') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('about_my_partner') ? 'about_my_partner_error' : '' }}">{{ old('about_my_partner', $member->about_my_partner) }}</textarea>
                        @error('about_my_partner')
                        <div id="about_my_partner_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        @php
            $profileRangeRows = old(
                'profile_ranges',
                $memberProfileRanges->map(fn ($range) => [
                    'range_from' => $range->range_from,
                    'range_to' => $range->range_to,
                    'price' => $range->price,
                ])->all()
            );
            $profileRangeRows = $profileRangeRows ?: [
                ['range_from' => '', 'range_to' => '', 'price' => ''],
            ];
        @endphp

        <div class="card border-0 shadow-sm mb-4 member-section">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-currency-rupee me-2"></i>
                    Profile View Rate
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Set the price charged according to how many profiles this member has viewed.
                </p>
                <div class="row g-2 d-none d-md-flex fw-semibold small text-muted mb-1">
                    <div class="col-md-4">From</div>
                    <div class="col-md-4">To</div>
                    <div class="col-md-4">Price</div>
                </div>

                <div id="profileRangeRows">
                @foreach($profileRangeRows as $index => $range)
                <div class="profile-range-row row g-2 mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label d-md-none" for="profile_range_from_{{ $index }}" id="profile_range_from_{{ $index }}_label">From</label>
                        <input type="number" name="profile_ranges[{{ $index }}][range_from]" id="profile_range_from_{{ $index }}"
                            class="form-control @error('profile_ranges.'.$index.'.range_from') is-invalid @enderror"
                            value="{{ $range['range_from'] ?? '' }}" min="1" aria-labelledby="profile_range_from_{{ $index }}_label" aria-invalid="{{ $errors->has('profile_ranges.'.$index.'.range_from') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('profile_ranges.'.$index.'.range_from') ? 'profile_range_from_'.$index.'_error' : '' }}">
                        @error('profile_ranges.'.$index.'.range_from')
                        <div id="profile_range_from_{{ $index }}_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-md-none" for="profile_range_to_{{ $index }}" id="profile_range_to_{{ $index }}_label">To</label>
                        <input type="number" name="profile_ranges[{{ $index }}][range_to]" id="profile_range_to_{{ $index }}"
                            class="form-control @error('profile_ranges.'.$index.'.range_to') is-invalid @enderror"
                            value="{{ $range['range_to'] ?? '' }}" min="1" aria-labelledby="profile_range_to_{{ $index }}_label" aria-invalid="{{ $errors->has('profile_ranges.'.$index.'.range_to') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('profile_ranges.'.$index.'.range_to') ? 'profile_range_to_'.$index.'_error' : '' }}">
                        @error('profile_ranges.'.$index.'.range_to')
                        <div id="profile_range_to_{{ $index }}_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-md-none" for="profile_range_price_{{ $index }}" id="profile_range_price_{{ $index }}_label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="profile_ranges[{{ $index }}][price]"
                                id="profile_range_price_{{ $index }}"
                                class="form-control @error('profile_ranges.'.$index.'.price') is-invalid @enderror"
                                value="{{ $range['price'] ?? '' }}" min="0" max="1000000" step="0.01" aria-labelledby="profile_range_price_{{ $index }}_label" aria-invalid="{{ $errors->has('profile_ranges.'.$index.'.price') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('profile_ranges.'.$index.'.price') ? 'profile_range_price_'.$index.'_error' : '' }}">
                        @error('profile_ranges.'.$index.'.price')
                        <div id="profile_range_price_{{ $index }}_error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-profile-range">Remove</button>
                    </div>
                </div>
                @endforeach
                </div>

                <button type="button" id="addProfileRange" class="btn btn-sm btn-outline-primary mt-1">
                    <i class="bi bi-plus-lg me-1"></i>Add Range
                </button>

                @error('profile_ranges')
                <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </form>


    @include('admin.members.partials.identity-proof')

    {{-- Save Buttons --}}
    <div class="d-flex justify-content-end gap-2 mb-5">
        <a
            href="{{ route('admin.members.show', $member->id) }}"
            class="btn btn-light">
            Cancel
        </a>

        <button
            type="submit"
            form="member-edit-form"
            class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i>
            Save Changes
        </button>
    </div>

</div>

@push('styles')
<style>
    .member-section .card-header {
        padding: 1.5rem 1.5rem 0;
        background: #fff;
        border: 0;
    }

    .member-section .card-header h5 {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        font-size: 1.1rem;
    }

    .member-section .card-header h5>i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin-right: 1rem !important;
        border-radius: 50%;
        background: var(--bs-primary-bg-subtle);
        color: var(--bs-primary);
        font-size: 1.15rem;
    }

    .member-section .card-body {
        padding: 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('member-edit-form');
        const country = document.getElementById('country_living_in');
        const state = document.getElementById('state_living_in');
        const city = document.getElementById('city_living_in');
        const partnerCountry = document.getElementById('partner_country');
        const partnerState = document.getElementById('partner_state');
        const partnerCity = document.getElementById('partner_city');
        const disability = document.getElementById('any_disability');
        const disabilityGroup = document.getElementById('disability_description_group');
        const disabilityDescription = document.getElementById('health_info');
        const idProofInput = document.getElementById('id_proof');
        const idProofPreview = document.getElementById('idProofPreview');
        const idProofPreviewContainer = document.getElementById('idProofPreviewContainer');
        const idProofEmpty = document.getElementById('idProofEmpty');
        const brothersSelect = document.getElementById('no_of_brothers');
        const marriedBrothersSelect = document.getElementById('married_brothers');
        const marriedBrothersZero = document.getElementById('married_brothers_zero');
        const sistersSelect = document.getElementById('no_of_sisters');
        const marriedSistersSelect = document.getElementById('married_sisters');
        const marriedSistersZero = document.getElementById('married_sisters_zero');

        function syncMarriedSiblingCount(totalSelect, marriedSelect, zeroInput) {
            if (!totalSelect || !marriedSelect || !zeroInput) return;

            const total = Number.parseInt(totalSelect.value, 10);
            const hasTotal = Number.isInteger(total);
            const hasNoSiblings = hasTotal && total === 0;

            marriedSelect.disabled = hasNoSiblings;
            zeroInput.disabled = !hasNoSiblings;
            zeroInput.name = hasNoSiblings ? marriedSelect.name : '';

            Array.from(marriedSelect.options).forEach(option => {
                if (option.value === '') return;
                option.disabled = hasTotal && Number(option.value) > total;
            });

            if (hasNoSiblings) {
                marriedSelect.value = '0';
            } else if (hasTotal && Number(marriedSelect.value) > total) {
                marriedSelect.value = String(total);
            }
        }

        brothersSelect?.addEventListener('change', () =>
            syncMarriedSiblingCount(brothersSelect, marriedBrothersSelect, marriedBrothersZero)
        );
        sistersSelect?.addEventListener('change', () =>
            syncMarriedSiblingCount(sistersSelect, marriedSistersSelect, marriedSistersZero)
        );
        syncMarriedSiblingCount(brothersSelect, marriedBrothersSelect, marriedBrothersZero);
        syncMarriedSiblingCount(sistersSelect, marriedSistersSelect, marriedSistersZero);

        const profileRangeRows = document.getElementById('profileRangeRows');
        const addProfileRangeButton = document.getElementById('addProfileRange');

        function addProfileRangeRow() {
            if (!profileRangeRows || profileRangeRows.children.length >= 20) return;

            const index = Date.now();
            const row = document.createElement('div');
            row.className = 'profile-range-row row g-2 mb-3 align-items-center';
            row.innerHTML = `
                <div class="col-md-4">
                    <label class="form-label d-md-none" for="profile_range_from_${index}" id="profile_range_from_${index}_label">From</label>
                    <input type="number" name="profile_ranges[${index}][range_from]" id="profile_range_from_${index}" aria-labelledby="profile_range_from_${index}_label" class="form-control" min="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label d-md-none" for="profile_range_to_${index}" id="profile_range_to_${index}_label">To</label>
                    <input type="number" name="profile_ranges[${index}][range_to]" id="profile_range_to_${index}" aria-labelledby="profile_range_to_${index}_label" class="form-control" min="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label d-md-none" for="profile_range_price_${index}" id="profile_range_price_${index}_label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="profile_ranges[${index}][price]" id="profile_range_price_${index}" aria-labelledby="profile_range_price_${index}_label" class="form-control" min="0" max="1000000" step="0.01">
                    </div>
                </div>
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-profile-range">Remove</button>
                </div>`;
            profileRangeRows.appendChild(row);
        }

        addProfileRangeButton?.addEventListener('click', addProfileRangeRow);
        profileRangeRows?.addEventListener('click', event => {
            const removeButton = event.target.closest('.remove-profile-range');
            if (!removeButton) return;

            removeButton.closest('.profile-range-row')?.remove();
            if (!profileRangeRows.children.length) addProfileRangeRow();
        });

        const selectedId = select => select?.selectedOptions?.[0]?.dataset?.id || '';
        const option = item => {
            const element = document.createElement('option');
            element.value = item.name;
            element.textContent = item.name;
            element.dataset.id = item.id;
            return element;
        };

        function setupLocation(countrySelect, stateSelect, citySelect) {
            let stateVersion = 0;
            let cityVersion = 0;

            function populate(select, items, selected, placeholder) {
                select.innerHTML = '';
                select.appendChild(option({ name: '', id: '' }));
                select.options[0].textContent = placeholder;
                items.forEach(item => select.appendChild(option(item)));
                if (selected && !items.some(item => item.name === selected)) {
                    select.appendChild(option({ name: selected, id: '' }));
                }
                select.value = selected;
                select.disabled = false;
                select.setCustomValidity('');
            }

            async function fetchOptions(select, url, selected, isCurrent, placeholder) {
                select.dataset.loading = 'true';
                try {
                    const response = await fetch(url, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!response.ok) throw new Error('Unable to load locations');
                    const items = await response.json();
                    if (!isCurrent()) return false;
                    populate(select, items, selected, placeholder);
                    select.setCustomValidity('');
                    return true;
                } catch (error) {
                    if (isCurrent()) {
                        // Retain saved values when the lookup service is unavailable.
                        populate(select, [], selected, 'Unable to load locations; reselect the parent to retry');
                    }
                    return false;
                } finally {
                    if (isCurrent()) delete select.dataset.loading;
                }
            }

            async function loadCities(selected = '') {
                const version = ++cityVersion;
                const id = selectedId(stateSelect);
                populate(citySelect, [], selected, 'Select City');
                if (!id) {
                    delete citySelect.dataset.loading;
                    return;
                }
                await fetchOptions(citySelect,
                    form.dataset.citiesUrl.replace('__ID__', encodeURIComponent(id)),
                    selected, () => version === cityVersion, 'Select City');
            }

            async function loadStates(selectedState = '', selectedCity = '') {
                const version = ++stateVersion;
                ++cityVersion;
                delete citySelect.dataset.loading;
                populate(stateSelect, [], selectedState, 'Select State');
                populate(citySelect, [], selectedCity, 'Select City');
                const id = selectedId(countrySelect);
                if (!id) {
                    delete stateSelect.dataset.loading;
                    return;
                }
                const loaded = await fetchOptions(stateSelect,
                    form.dataset.statesUrl.replace('__ID__', encodeURIComponent(id)),
                    selectedState, () => version === stateVersion, 'Select State');
                if (loaded && selectedState) await loadCities(selectedCity);
            }

            countrySelect.addEventListener('change', () => loadStates());
            stateSelect.addEventListener('change', () => loadCities());
            if (countrySelect.value) loadStates(stateSelect.dataset.current, citySelect.dataset.current);
        }

        setupLocation(country, state, city);
        setupLocation(partnerCountry, partnerState, partnerCity);

        form.addEventListener('submit', event => {
            const loading = [state, city, partnerState, partnerCity].find(select => select.dataset.loading);
            if (loading) {
                event.preventDefault();
                loading.setCustomValidity('Please wait for locations to finish loading.');
                loading.reportValidity();
            }
        });

        function toggleDisabilityDescription(clearWhenHidden = false) {
            const visible = disability?.value === 'Yes';
            disabilityGroup?.classList.toggle('d-none', !visible);
            if (disabilityDescription) {
                disabilityDescription.required = visible;
                if (!visible && clearWhenHidden) disabilityDescription.value = '';
            }
        }

        disability?.addEventListener('change', () => toggleDisabilityDescription(true));
        toggleDisabilityDescription();

        idProofInput?.addEventListener('change', function() {
            const file = this.files?.[0];
            if (!file) return;

            idProofPreview.src = URL.createObjectURL(file);
            idProofPreviewContainer?.classList.remove('d-none');
            idProofEmpty?.classList.add('d-none');
        });
    });
</script>
@endpush

@endsection
