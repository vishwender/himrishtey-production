@php
    $newMembersOnly = $newMembersOnly ?? false;
    $bannedMembersOnly = $bannedMembersOnly ?? false;
@endphp
{{-- Members Table --}}
@php
$hideRelationshipManagerColumn = request()->routeIs('admin.members.index')
    && (app(\App\Services\RelationshipManagerAccess::class)->isRestricted()
        || (auth('admin')->user()?->isSiteAdmin()
            && request('relationship_manager') === auth('admin')->user()->name));
@endphp
<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 members-table">

                <thead class="table-light">

                    <tr>

                        @if($newMembersOnly)
                        <th><input type="checkbox" id="select-new-members" aria-label="Select all members on this page"></th>
                        @endif
                        <th>Photo</th>

                        <th>Profile ID</th>
                        <th>Member</th>

                        <th>Gender</th>

                        <th>Registration</th>

                        <th>Membership</th>

                        @if($newMembersOnly)
                        <th>Profile %</th>

                        <th>Assigned To</th>

                        <th>Source</th>

                        @else
                        @unless($hideRelationshipManagerColumn)
                        <th>Relationship Manager</th>
                        @endunless

                        <th>Status</th>
                        @endif

                        <th class="text-end members-actions">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($members as $member)

                    <tr>

                        @if($newMembersOnly)
                        <td><input type="checkbox" class="new-member-checkbox" name="member_ids[]" value="{{ $member->id }}" form="assign-new-members" aria-label="Select {{ $member->profile_id }}"></td>
                        @endif
                        <td>@php
$tablePhotoUrl = app(\App\Services\MemberPhotoService::class)->url($member->photo ?? null);
@endphp
<div class="rounded bg-body-secondary d-flex align-items-center justify-content-center overflow-hidden" style="width:56px;height:64px;">
    @if($tablePhotoUrl)
    <img src="{{ $tablePhotoUrl }}" alt="{{ \Illuminate\Support\Str::ucfirst((string) ($member->full_name)) }}" width="56" height="64" style="object-fit:cover;" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.classList.remove('d-none');">
    @endif
    <span class="text-muted {{ $tablePhotoUrl ? 'd-none' : '' }}" role="img" aria-label="No member photo"><i class="bi bi-image fs-4" aria-hidden="true"></i></span>
</div></td>
                        {{-- Profile ID --}}
                        <td>

                            <strong>
                                {{ $member->profile_id }}
                            </strong>

                        </td>


                        {{-- Member contact details --}}
                        <td><strong>{{ \Illuminate\Support\Str::ucfirst((string) ($member->full_name)) }}</strong>
<div class="small text-muted mt-1 d-flex align-items-center gap-2">
    <i class="bi bi-envelope" aria-hidden="true" title="Email"></i>
    <span>{{ $member->email ?: '—' }}</span>
</div>
<div class="small text-muted mt-1 d-flex align-items-center gap-2">
    <i class="bi bi-telephone" aria-hidden="true" title="Phone"></i>
    <span>{{ $member->mobile_number ?: '—' }}</span>
</div></td>

                        {{-- Gender --}}
                        <td>
                            {{ $member->gender }}
                        </td>


                        {{-- Registration --}}
                        <td>
                            {{ $member->registration_date ? \Carbon\Carbon::parse($member->registration_date)->format('Y-m-d g:i A') : '—' }}
                        </td>

                        <td>
                            @if(!empty($member->membership_plan_name))

                            @php
                            $planName = strtolower($member->membership_plan_name);

                            $planClass = match (true) {
                            str_contains($planName, 'platinum'),
                            str_contains($planName, 'vip') => 'bg-danger',

                            str_contains($planName, 'premium') => 'bg-primary',

                            str_contains($planName, 'gold') => 'bg-warning text-dark',

                            str_contains($planName, 'silver') => 'bg-info text-dark',

                            str_contains($planName, 'basic'),
                            str_contains($planName, 'free') => 'bg-secondary',

                            default => 'bg-dark',
                            };
                            @endphp

                            <span class="badge {{ $planClass }}">
                                {{ $member->membership_plan_name }}
                            </span>

                            @else

                            <span class="badge bg-light text-muted border">
                                No Plan
                            </span>

                            @endif
                        </td>

                        @if($newMembersOnly)
                        <td>{{ $member->profile_completed ?? 0 }}%</td>
                        <td>{{ $member->assigned_to ?: 'Unassigned' }}</td>
                        <td>{{ $member->register_through ?: '—' }}</td>
                        @else
                        {{-- Relationship Manager --}}
                        @unless($hideRelationshipManagerColumn)
                        <td>
                            @if(!empty($member->relationship_manager))
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-person-badge me-1"></i>
                                {{ $member->relationship_manager }}
                            </span>
                            @else
                            <span class="text-muted">Unassigned</span>
                            @endif
                        </td>
                        @endunless

                        {{-- Status --}}
                        <td>

                            @if($member->active === 'Yes')

                            <span class="badge bg-success">
                                Active
                            </span>

                            @else

                            <span class="badge {{ $member->active === 'Banned' ? 'bg-danger' : 'bg-secondary' }}">
                                {{ $member->active === 'Banned' ? 'Banned' : 'Inactive' }}
                            </span>

                            @endif

                        </td>


                        @endif

                        <td class="text-end members-actions">

                            <div class="dropdown">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                    <i class="bi bi-three-dots-vertical"></i>
                                    Actions

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                    {{-- View Profile --}}
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('admin.members.show', $member->id) }}">

                                            <i class="bi bi-person me-2 text-primary"></i>
                                            View Profile

                                        </a>
                                    </li>


                                    @if($bannedMembersOnly)
                                    <li><a class="dropdown-item" href="{{ route('admin.members.show', $member->id) }}#identity-proof">Identity Proof</a></li>
                                    @endif

                                    {{-- Edit Profile --}}

                                    @if(auth('admin')->user()?->hasPermission('edit-member'))

                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('admin.members.edit', $member->id) }}">

                                            <i class="bi bi-pencil me-2 text-primary"></i>
                                            Edit Profile

                                        </a>
                                    </li>

                                    @endif

                                    <li>
                                        <button
                                            type="button"
                                            class="dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#remarkModal"
                                            data-remark-action="{{ route('admin.members.remarks.update', $member->id) }}"
                                            data-member-name="{{ \Illuminate\Support\Str::ucfirst((string) ($member->full_name)) }}">
                                            <i class="bi bi-chat-left-text me-2 text-warning"></i>
                                            Remarks
                                        </button>
                                    </li>

                                    @if(auth('admin')->user()?->hasPermission('add-rotations'))

                                    <li>
                                        <button
                                            type="button"
                                            class="dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rotationModal"
                                            data-member-id="{{ $member->id }}"
                                            data-member-name="{{ \Illuminate\Support\Str::ucfirst((string) ($member->full_name)) }}">

                                            <i class="bi bi-arrow-repeat me-2"></i>
                                            Add Rotation

                                        </button>
                                    </li>

                                    @endif


                                    {{-- Activity --}}
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('admin.activities.member', [
                        'memberId' => $member->id,
                        'activity' => 'shortlisted'
                    ]) }}">

                                            <i class="bi bi-activity me-2 text-info"></i>
                                            View Activity

                                        </a>
                                    </li>


                                    {{-- Photos --}}
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('admin.members.show', $member->id) }}#gallery">

                                            <i class="bi bi-images me-2 text-info"></i>
                                            Manage Photos

                                        </a>
                                    </li>


                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>


                                    {{-- Trusted and promoted member actions are temporarily disabled. --}}
                                    @if(false)
                                    {{-- Trusted --}}
                                    <li>

                                        <form
                                            action="{{ route('admin.members.toggle-trusted', $member->id) }}"
                                            method="POST"
                                            class="member-action-form"
                                            data-confirm-title="Change Trusted Status"
                                            data-confirm="Are you sure you want to change this member's trusted status?">

                                            @csrf

                                            <button type="submit" class="dropdown-item">
                                                @if($member->is_trusted === 'Yes')
                                                <i class="bi bi-patch-check-fill me-2 text-success"></i>
                                                Remove Trusted
                                                @else
                                                <i class="bi bi-patch-check me-2 text-success"></i>
                                                Mark as Trusted
                                                @endif
                                            </button>

                                        </form>

                                    </li>


                                    {{-- Promoted --}}
                                    <li>

                                        <form
                                            action="{{ route('admin.members.toggle-promoted', $member->id) }}"
                                            method="POST"
                                            class="member-action-form"
                                            data-confirm-title="Change Promotion Status"
                                            data-confirm="Are you sure you want to change this member's promotion status?">

                                            @csrf

                                            <button type="submit" class="dropdown-item">
                                                @if($member->promoted === 'Yes')
                                                <i class="bi bi-star-fill me-2 text-warning"></i>
                                                Remove Promotion
                                                @else
                                                <i class="bi bi-star me-2 text-warning"></i>
                                                Promote Member
                                                @endif
                                            </button>

                                        </form>

                                    </li>
                                    @endif


                                    @if($member->active !== 'Banned')
                                    {{-- Active / Inactive --}}
                                    <li>
                                        @if($member->active !== 'Yes')
                                        @if(auth('admin')->user()?->hasAnyPermission(['manage-member-status', 'edit-member', 'edit-members']))
                                        <a class="dropdown-item" href="{{ route('admin.members.activation.create', $member->id) }}">
                                            <i class="bi bi-person-check me-2 text-success"></i>Activate Member
                                        </a>
                                        @endif
                                        @else
                                        @if(auth('admin')->user()?->hasAnyPermission(['manage-member-status', 'edit-member', 'edit-members']))
                                        <form
                                            action="{{ route('admin.members.toggle-status', $member->id) }}"
                                            method="POST"
                                            class="member-action-form"
                                            data-confirm-title="Change Member Status"
                                            data-confirm="Are you sure you want to change this member's active status?">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                @if($member->active === 'Yes')
                                                <i class="bi bi-person-x me-2 text-danger"></i>
                                                Deactivate Member
                                                @else
                                                <i class="bi bi-person-check me-2 text-success"></i>
                                                Activate Member
                                                @endif
                                            </button>
                                        </form>
                                        @endif
                                    </li>
                                    @endif
                                    @endif

                                    {{-- Visibility --}}
                                    <li>

                                        <form
                                            action="{{ route('admin.members.toggle-visibility', $member->id) }}"
                                            method="POST"
                                            class="member-action-form"
                                            data-confirm-title="Change Profile Visibility"
                                            data-confirm="Are you sure you want to change this member's profile visibility?">

                                            @csrf

                                            <button type="submit" class="dropdown-item">
                                                @if($member->profile_hide === 'Yes')
                                                <i class="bi bi-eye me-2 text-success"></i>
                                                Show Profile
                                                @else
                                                <i class="bi bi-eye-slash me-2 text-warning"></i>
                                                Hide Profile
                                                @endif
                                            </button>

                                        </form>

                                    </li>


                                    @if(auth('admin')->user()?->hasAnyRole(['super-admin', 'admin']))
                                    <li>
                                        <form method="POST" action="{{ route('admin.members.ban.update', $member->id) }}" class="member-action-form" data-confirm-title="Change Ban Status" data-confirm="{{ $member->active === 'Banned' ? 'Unban and activate this member?' : 'Ban this member?' }}">
                                            @csrf
                                            <input type="hidden" name="banned" value="{{ $member->active === 'Banned' ? 0 : 1 }}">
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi {{ $member->active === 'Banned' ? 'bi-person-check' : 'bi-person-slash' }} me-2"></i>
                                                {{ $member->active === 'Banned' ? 'Unban Member' : 'Ban Member' }}
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    @if(auth('admin')->user()?->canRaiseProfileDeleteRequest())

                                    <li>
                                        <button type="button"
                                            class="dropdown-item text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteRequestModal"
                                            data-action="{{ route('admin.members.delete-request', $member->id) }}"
                                            data-member-name="{{ \Illuminate\Support\Str::ucfirst((string) ($member->full_name)) }}"
                                            data-profile-id="{{ $member->profile_id }}"
                                            >
                                            <i class="bi bi-trash3 me-2"></i>
                                            Delete Profile
                                        </button>
                                    </li>

                                    @endif

                                </ul>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="{{ $newMembersOnly ? 11 : (9 - ($hideRelationshipManagerColumn ? 1 : 0)) }}"
                            class="text-center py-5 text-muted">
                            No members found.
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Pagination --}}
    @if($members->hasPages())

    <div class="card-footer bg-white">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

            <div class="text-muted small">

                Showing
                <strong>{{ $members->firstItem() }}</strong>
                to
                <strong>{{ $members->lastItem() }}</strong>
                of
                <strong>{{ $members->total() }}</strong>
                members

            </div>

            <div>
                {{ $members->onEachSide(1)->links() }}
            </div>

        </div>

    </div>

    @endif

</div>
