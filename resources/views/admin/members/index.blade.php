@extends('admin.layout')

@section('title', $newMembersOnly ? 'New Members' : ($bannedMembersOnly ? 'Banned Members' : 'Members'))
@section('page-title', $newMembersOnly ? 'New Members' : ($bannedMembersOnly ? 'Banned Members' : 'Members'))

@section('content')

<div class="container-fluid members-page">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 members-page-header">

        <div>
            <h1 class="h3 mb-1">
                {{ $newMembersOnly ? 'New Members' : ($bannedMembersOnly ? 'Banned Members' : 'Members') }}
            </h1>

            <p class="text-muted mb-0">
                {{ $newMembersOnly
                    ? 'Review profiles waiting to be activated.'
                    : ($bannedMembersOnly ? 'Review and manage banned profiles of the selected site.' : 'Manage members of the selected site.') }}
            </p>
        </div>
        <a
            href="{{ route('admin.members.create') }}"
            class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>

            Add Member

        </a>
    </div>


    {{-- Search / Filter --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route($newMembersOnly ? 'admin.members.new' : ($bannedMembersOnly ? 'admin.members.banned' : 'admin.members.index')) }}">

                <div class="row g-3">

                    <div class="col-lg-2 col-md-6">
                        <label for="new-member-gender" class="form-label">Gender</label>
                        <select id="new-member-gender" name="gender" class="form-select">
                            <option value="">All genders</option>
                            @foreach(['Male', 'Female'] as $gender)
                            <option value="{{ $gender }}" @selected(request('gender')===$gender)>{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($newMembersOnly || $bannedMembersOnly)
                    <div class="col-lg-2 col-md-6">
                        <label for="new-member-page-size" class="form-label">Per page</label>
                        <select id="new-member-page-size" name="per_page" class="form-select" onchange="this.form.submit()">
                            @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" @selected($members->perPage() === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    {{-- Search --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Search Member
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Profile ID, name, email or mobile">

                        </div>

                    </div>

                    {{-- Relationship Manager --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Relationship Manager
                        </label>

                        <select
                            name="relationship_manager"
                            class="form-select">

                            <option value="">All Relationship Managers</option>
                            <option
                                value="__unassigned"
                                {{ request('relationship_manager') === '__unassigned' ? 'selected' : '' }}>
                                Unassigned
                            </option>

                            @foreach($relationshipManagers as $manager)
                            <option
                                value="{{ $manager->name }}"
                                {{ request('relationship_manager') === $manager->name ? 'selected' : '' }}>
                                {{ $manager->name }}
                                @if($manager->profile_id)
                                ({{ $manager->profile_id }})
                                @endif
                            </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option
                                value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- Trusted 
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Trusted
                        </label>

                        <select
                            name="trusted"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="yes"
                                {{ request('trusted') === 'yes' ? 'selected' : '' }}>

                    Trusted

                    </option>

                    <option
                        value="no"
                        {{ request('trusted') === 'no' ? 'selected' : '' }}>

                        Not Trusted

                    </option>

                    </select>

                </div> --}}

                {{-- Banned Status 
                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Banned
                    </label>

                    <select
                        name="banned"
                        class="form-select">

                        <option value="">
                            All
                        </option>

                        <option
                            value="yes"
                            {{ request('banned') === 'yes' ? 'selected' : '' }}>
                Banned
                </option>

                <option
                    value="no"
                    {{ request('banned') === 'no' ? 'selected' : '' }}>
                    Not Banned
                </option>

                </select>

        </div> --}}


        {{-- Promoted -
        <div class="col-lg-2 col-md-6">

            <label class="form-label fw-semibold">
                Promoted
            </label>

            <select
                name="promoted"
                class="form-select">

                <option value="">
                    All
                </option>

                <option
                    value="yes"
                    {{ request('promoted') === 'yes' ? 'selected' : '' }}>

        Promoted

        </option>

        <option
            value="no"
            {{ request('promoted') === 'no' ? 'selected' : '' }}>

            Not Promoted

        </option>

        </select>

    </div> -}}


    {{-- Visibility --}}
    <div class="col-lg-2 col-md-6">

        <label class="form-label fw-semibold">
            Visibility
        </label>

        <select
            name="visibility"
            class="form-select">

            <option value="">
                All
            </option>

            <option
                value="visible"
                {{ request('visibility') === 'visible' ? 'selected' : '' }}>

                Visible

            </option>

            <option
                value="hidden"
                {{ request('visibility') === 'hidden' ? 'selected' : '' }}>

                Hidden

            </option>

        </select>

    </div>

    {{-- Membership Plan --}}

    <div class="col-lg-2 col-md-6">

        <label class="form-label fw-semibold">
            Membership Plan
        </label>

        <select
            name="plan_id"
            class="form-select">

            <option value="">
                All Plans
            </option>

            <option
                value="none"
                {{ request('plan_id') === 'none' ? 'selected' : '' }}>

                No Plan

            </option>

            @foreach($plans as $plan)

            <option
                value="{{ $plan->id }}"
                {{ request()->filled('plan_id') &&
                                    (string) request('plan_id') === (string) $plan->id
                                    ? 'selected'
                                    : '' }}>

                {{ $plan->plan_name }}

            </option>

            @endforeach

        </select>

    </div>

    {{-- Sort --}}

    <div class="col-lg-2 col-md-6">

        <label class="form-label fw-semibold">
            Sort By
        </label>

        <select
            name="sort"
            class="form-select">

            <option
                value="newest"
                {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>
                Newest First
            </option>

            <option
                value="oldest"
                {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                Oldest First
            </option>

            <option
                value="name_asc"
                {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                Name A-Z
            </option>

            <option
                value="name_desc"
                {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                Name Z-A
            </option>

            <option
                value="profile_asc"
                {{ request('sort') === 'profile_asc' ? 'selected' : '' }}>
                Profile ID A-Z
            </option>

            <option
                value="profile_desc"
                {{ request('sort') === 'profile_desc' ? 'selected' : '' }}>
                Profile ID Z-A
            </option>

        </select>

    </div>


    {{-- Buttons --}}
    <div class="col-12">

        <div class="d-flex flex-wrap gap-2">

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-funnel me-1"></i>
                Apply Filters

            </button>


            <a
                href="{{ route($newMembersOnly ? 'admin.members.new' : ($bannedMembersOnly ? 'admin.members.banned' : 'admin.members.index')) }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-arrow-counterclockwise me-1"></i>
                Reset

            </a>

        </div>

    </div>

</div>

</form>

</div>

</div>

{{-- =========================================================
    Active Filters
========================================================= --}}

@php
$hasFilters =
request()->filled('search') ||
request()->filled('gender') ||
request()->filled('status') ||
request()->filled('trusted') ||
request()->filled('promoted') ||
request()->filled('visibility') ||
request()->filled('banned') ||
request()->filled('plan_id') ||
request()->filled('relationship_manager') ||
request()->filled('sort');
@endphp


@if($hasFilters)

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body py-3">

        <div class="d-flex flex-wrap align-items-center gap-2">

            <span class="fw-semibold text-muted me-1">
                <i class="bi bi-funnel me-1"></i>
                Active Filters:
            </span>


            {{-- Search --}}

            @if(request()->filled('search'))

            <span class="badge bg-primary d-flex align-items-center gap-1 text-wrap text-start">

                Search:
                {{ request('search') }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                                'search' => null,
                                'page' => null
                            ]) }}"
                    class="text-white text-decoration-none ms-1"
                    title="Remove search">

                    &times;

                </a>

            </span>

            @endif

            @if(request()->filled('gender'))
            <span class="badge bg-primary d-flex align-items-center gap-1 text-wrap text-start">
                Gender: {{ request('gender') }}
                <a
                    href="{{ request()->fullUrlWithQuery(['gender' => null, 'page' => null]) }}"
                    class="text-white text-decoration-none ms-1"
                    title="Remove gender filter"
                    aria-label="Remove gender filter">
                    &times;
                </a>
            </span>
            @endif

            {{-- Relationship Manager --}}

            @if(request()->filled('relationship_manager'))

            <span class="badge bg-primary d-flex align-items-center gap-1 text-wrap text-start">

                Relationship Manager:
                {{ request('relationship_manager') === '__unassigned'
                        ? 'Unassigned'
                        : request('relationship_manager') }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                                'relationship_manager' => null,
                                'page' => null
                            ]) }}"
                    class="text-white text-decoration-none ms-1"
                    title="Remove relationship manager filter">

                    &times;

                </a>

            </span>

            @endif


            {{-- Status --}}

            @if(request()->filled('status'))

            <span class="badge bg-secondary d-flex align-items-center gap-1">

                Status:
                {{ request('status') === 'active' ? 'Active' : 'Inactive' }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                                'status' => null,
                                'page' => null
                            ]) }}"
                    class="text-white text-decoration-none ms-1"
                    title="Remove status">

                    &times;

                </a>

            </span>

            @endif


            {{-- Trusted --}}

            @if(request()->filled('trusted'))

            <span class="badge bg-info text-dark d-flex align-items-center gap-1">

                Trusted:
                {{ request('trusted') === 'yes' ? 'Yes' : 'No' }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                                'trusted' => null,
                                'page' => null
                            ]) }}"
                    class="text-dark text-decoration-none ms-1"
                    title="Remove trusted filter">

                    &times;

                </a>

            </span>

            @endif

            {{-- Banned --}}

            @if(request()->filled('banned'))

            <span class="badge bg-danger d-flex align-items-center gap-1">

                Banned:
                {{ request('banned') === 'yes' ? 'Yes' : 'No' }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                'banned' => null,
                'page' => null
            ]) }}"
                    class="text-white text-decoration-none ms-1"
                    title="Remove banned filter">

                    &times;

                </a>

            </span>

            @endif


            {{-- Promoted --}}

            @if(request()->filled('promoted'))

            <span class="badge bg-warning text-dark d-flex align-items-center gap-1">

                Promoted:
                {{ request('promoted') === 'yes' ? 'Yes' : 'No' }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                                'promoted' => null,
                                'page' => null
                            ]) }}"
                    class="text-dark text-decoration-none ms-1"
                    title="Remove promoted filter">

                    &times;

                </a>

            </span>

            @endif


            {{-- Visibility --}}

            @if(request()->filled('visibility'))

            <span class="badge bg-dark d-flex align-items-center gap-1">

                Visibility:
                {{ request('visibility') === 'visible' ? 'Visible' : 'Hidden' }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                                'visibility' => null,
                                'page' => null
                            ]) }}"
                    class="text-white text-decoration-none ms-1"
                    title="Remove visibility filter">

                    &times;

                </a>

            </span>

            @endif


            {{-- Membership Plan --}}

            @if(request()->filled('plan_id'))

            @php
            $selectedPlan = null;

            if (request('plan_id') !== 'none') {
            $selectedPlan = $plans->firstWhere(
            'id',
            request('plan_id')
            );
            }
            @endphp


            <span class="badge bg-primary d-flex align-items-center gap-1 text-wrap text-start">

                Plan:

                @if(request('plan_id') === 'none')

                No Plan

                @elseif($selectedPlan)

                {{ $selectedPlan->plan_name }}

                @else

                Unknown Plan

                @endif


                <a
                    href="{{ request()->fullUrlWithQuery([
                                'plan_id' => null,
                                'page' => null
                            ]) }}"
                    class="text-white text-decoration-none ms-1"
                    title="Remove membership filter">

                    &times;

                </a>

            </span>

            @endif


            {{-- Sort --}}

            @if(request()->filled('sort'))

            @php
            $sortLabels = [
            'newest' => 'Newest First',
            'oldest' => 'Oldest First',
            'name_asc' => 'Name A-Z',
            'name_desc' => 'Name Z-A',
            'profile_asc' => 'Profile ID A-Z',
            'profile_desc' => 'Profile ID Z-A',
            ];

            $sortLabel = $sortLabels[request('sort')] ?? request('sort');
            @endphp


            <span class="badge bg-light text-dark border d-flex align-items-center gap-1">

                Sort:
                {{ $sortLabel }}

                <a
                    href="{{ request()->fullUrlWithQuery([
                                'sort' => null,
                                'page' => null
                            ]) }}"
                    class="text-dark text-decoration-none ms-1"
                    title="Remove sorting">

                    &times;

                </a>

            </span>

            @endif


            {{-- Clear All --}}

            <a
                href="{{ route($newMembersOnly ? 'admin.members.new' : ($bannedMembersOnly ? 'admin.members.banned' : 'admin.members.index')) }}"
                class="btn btn-sm btn-outline-danger ms-1">

                <i class="bi bi-x-circle me-1"></i>
                Clear All

            </a>

        </div>

    </div>

</div>

@endif


@if($newMembersOnly)
<div class="d-flex flex-wrap align-items-center gap-3 mb-3">
    <span class="badge bg-primary">{{ $members->total() }} new members</span>
    @if(auth('admin')->user()?->hasPermission('edit-member') && !app(\App\Services\RelationshipManagerAccess::class)->isRestricted())
    <form id="assign-new-members" method="POST" action="{{ route('admin.members.new.assign-staff') }}" class="d-flex flex-wrap gap-2">
        @csrf
        <select name="staff_id" class="form-select" aria-label="Assign staff" required>
            <option value="">Select staff</option>
            @foreach($assignmentStaff as $manager)
            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary text-nowrap" type="submit">Assign Staff</button>
    </form>
    @endif
</div>
@endif
@include('admin.members.partials.member-table')

</div>

@include('admin.members.partials.table-actions')

@endsection
