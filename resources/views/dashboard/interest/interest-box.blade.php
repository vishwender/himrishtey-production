@extends('layouts.dashboard')

@section('title','Interest Box - Him Rishtey')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/css/interests.css') }}" />
@endsection

@section('content')
<!-- ── MAIN ── -->
<main class="main-content" id="int-main">

    <!-- PAGE HEADER -->
    <div class="int-page-header container-xxl">
        <div class="int-page-title-row">
            <div class="int-page-title-wrap">
                <i data-lucide="inbox" width="22" height="22" class="int-title-icon" aria-hidden="true"></i>
                <h1 class="int-page-title">Interest Box</h1>
            </div>

            <!-- Received / Sent Toggle -->
            <div class="int-toggle-wrap" role="group" aria-label="View received or sent interests">
                <button class="int-toggle-btn active" id="btnReceived" data-mode="received" aria-pressed="true">
                    <i data-lucide="download" width="14" height="14"></i>
                    Received
                </button>
                <button class="int-toggle-btn" id="btnSent" data-mode="sent" aria-pressed="false">
                    <i data-lucide="send" width="14" height="14"></i>
                    Sent
                </button>
            </div>
        </div>

        <!-- Summary counts -->
        <div class="int-summary-row" id="intSummaryRow">
            <div class="int-summary-chip pending" id="summaryPending">
                <i data-lucide="clock" width="13" height="13"></i>
                <span class="sc-num" id="cntPending">00</span> Pending
            </div>
            <div class="int-summary-chip accepted" id="summaryAccepted">
                <i data-lucide="check-circle" width="13" height="13"></i>
                <span class="sc-num" id="cntAccepted">0</span> Accepted
            </div>
            <div class="int-summary-chip rejected" id="summaryRejected">
                <i data-lucide="x-circle" width="13" height="13"></i>
                <span class="sc-num" id="cntRejected">0</span> Rejected
            </div>
        </div>
    </div>

    <!-- TAB BAR -->
    <div class="int-tabbar-wrap" role="tablist" aria-label="Interest status tabs" id="intTabBar">
        <button class="int-tab active" role="tab" id="tabPending" aria-selected="true" aria-controls="panelPending" data-tab="pending">
            <i data-lucide="clock" width="15" height="15"></i>
            <span>Pending</span>
            <span class="int-tab-badge" id="badgePending">0</span>
        </button>
        <button class="int-tab" role="tab" id="tabAccepted" aria-selected="false" aria-controls="panelAccepted" data-tab="accepted">
            <i data-lucide="check-circle" width="15" height="15"></i>
            <span>Accepted</span>
            <span class="int-tab-badge accepted" id="badgeAccepted">0</span>
        </button>
        <button class="int-tab" role="tab" id="tabRejected" aria-selected="false" aria-controls="panelRejected" data-tab="rejected">
            <i data-lucide="x-circle" width="15" height="15"></i>
            <span>Rejected</span>
            <span class="int-tab-badge rejected" id="badgeRejected">0</span>
        </button>
    </div>

    <!-- TAB PANELS -->
    <div class="int-panels-wrap container-xxl">

        <!-- PENDING -->
        <section class="int-panel" id="panelPending" role="tabpanel" aria-labelledby="tabPending">
            <div class="int-grid" id="gridPending" role="list" aria-label="Pending interests"></div>
            <div class="int-empty" id="emptyPending" style="display:none;">
                <div class="int-empty-icon"><i data-lucide="inbox" width="32" height="32"></i></div>
                <h3>No pending interests</h3>
                <p id="emptyPendingMsg">You have no pending interest requests right now.</p>
            </div>
            <div class="int-skeleton-grid" id="skelPending">
                <div class="int-skel-card">
                    <div class="int-skel-img"></div>
                    <div class="int-skel-body">
                        <div class="int-skel-line w55"></div>
                        <div class="int-skel-line w35"></div>
                        <div class="int-skel-line w70"></div>
                    </div>
                </div>
                <div class="int-skel-card">
                    <div class="int-skel-img"></div>
                    <div class="int-skel-body">
                        <div class="int-skel-line w55"></div>
                        <div class="int-skel-line w35"></div>
                        <div class="int-skel-line w70"></div>
                    </div>
                </div>
                <div class="int-skel-card">
                    <div class="int-skel-img"></div>
                    <div class="int-skel-body">
                        <div class="int-skel-line w55"></div>
                        <div class="int-skel-line w35"></div>
                        <div class="int-skel-line w70"></div>
                    </div>
                </div>
                <div class="int-skel-card">
                    <div class="int-skel-img"></div>
                    <div class="int-skel-body">
                        <div class="int-skel-line w55"></div>
                        <div class="int-skel-line w35"></div>
                        <div class="int-skel-line w70"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ACCEPTED -->
        <section class="int-panel" id="panelAccepted" role="tabpanel" aria-labelledby="tabAccepted" hidden>
            <div class="int-grid" id="gridAccepted" role="list" aria-label="Accepted interests"></div>
            <div class="int-empty" id="emptyAccepted" style="display:none;">
                <div class="int-empty-icon accepted"><i data-lucide="heart" width="32" height="32"></i></div>
                <h3>No accepted interests yet</h3>
                <p id="emptyAcceptedMsg">Accepted matches will appear here.</p>
            </div>
            <div class="int-skeleton-grid" id="skelAccepted" style="display:none;"></div>
        </section>

        <!-- REJECTED -->
        <section class="int-panel" id="panelRejected" role="tabpanel" aria-labelledby="tabRejected" hidden>
            <div class="int-grid" id="gridRejected" role="list" aria-label="Rejected interests"></div>
            <div class="int-empty" id="emptyRejected" style="display:none;">
                <div class="int-empty-icon rejected"><i data-lucide="x-circle" width="32" height="32"></i></div>
                <h3>No rejected interests</h3>
                <p id="emptyRejectedMsg">Declined requests will appear here.</p>
            </div>
            <div class="int-skeleton-grid" id="skelRejected" style="display:none;"></div>
        </section>

    </div><!-- /panels-wrap -->
</main>
@endsection
<!-- ── ACCEPT / REJECT CONFIRMATION TOAST ── -->
<div class="int-toast" id="intToast" role="alert" aria-live="polite" aria-atomic="true">
    <span class="int-toast-icon" id="intToastIcon"></span>
    <span class="int-toast-msg" id="intToastMsg"></span>
</div>


@section('scripts')
<!-- Page JS -->
<script src="{{ asset('assets/js/interests.js') }}"></script>
@endsection
<script>
    window.ALL_INTERESTS = @json($data);
</script>
<script>
    const updateInterestUrl = "{{ route('interest.update.status') }}";
</script>