<div class="card">

    <div class="card-header">
        <h5 class="mb-0">Members</h5>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 members-table">

                <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Profile ID</th>
                        <th>Member</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Status</th>
                        <th class="text-end members-actions">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($members as $member)

                    <tr>

                        <td>@php
$tablePhotoUrl = app(\App\Services\MemberPhotoService::class)->url($member->photo ?? null);
@endphp
<div class="rounded bg-body-secondary d-flex align-items-center justify-content-center overflow-hidden" style="width:56px;height:64px;">
    @if($tablePhotoUrl)
    <img src="{{ $tablePhotoUrl }}" alt="{{ \Illuminate\Support\Str::ucfirst((string) ($member->full_name)) }}" width="56" height="64" style="object-fit:cover;" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.classList.remove('d-none');">
    @endif
    <span class="text-muted {{ $tablePhotoUrl ? 'd-none' : '' }}" role="img" aria-label="No member photo"><i class="bi bi-image fs-4" aria-hidden="true"></i></span>
</div></td>
                        <td><strong>{{ $member->profile_id }}</strong></td>

                        <td><strong>{{ \Illuminate\Support\Str::ucfirst((string) ($member->full_name)) }}</strong>
<div class="small text-muted mt-1 d-flex align-items-center gap-2">
    <i class="bi bi-envelope" aria-hidden="true" title="Email"></i>
    <span>{{ $member->email ?: '—' }}</span>
</div>
<div class="small text-muted mt-1 d-flex align-items-center gap-2">
    <i class="bi bi-telephone" aria-hidden="true" title="Phone"></i>
    <span>{{ $member->mobile_number ?: '—' }}</span>
</div></td>

                        <td>
                            {{ $member->gender }}
                        </td>

                        <td>
                            {{-- Use your existing age logic here --}}
                            {{ $member->age ?? '-' }}
                        </td>

                        <td>
                            {{ $member->active }}
                        </td>

                        <td class="text-end members-actions">
                            <a
                                href="{{ route('admin.members.show', $member->id) }}"
                                class="btn btn-sm btn-outline-secondary">
                                View Profile
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="text-center py-4">
                            No members found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>