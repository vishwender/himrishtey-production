@if(($pendingActivationRequestCount ?? 0) > 0)
    <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" role="status">
        <div>
            <h2 class="h6 mb-1">
                <i class="bi bi-bell-fill me-2" aria-hidden="true"></i>
                Activation requests
                <span class="badge text-bg-warning ms-1">{{ number_format($pendingActivationRequestCount) }}</span>
            </h2>
            <p class="mb-0">
                {{ $pendingActivationRequestCount === 1 ? 'A new activation request is awaiting approval.' : 'Activation requests are awaiting approval.' }}
            </p>
        </div>
        <a href="{{ route('admin.members.activation.requests') }}" class="btn btn-sm btn-outline-dark">
            Review activation requests <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </a>
    </div>
@endif
