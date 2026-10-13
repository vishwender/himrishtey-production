{{-- =========================================================
    REMARK MODAL
========================================================= --}}

<div
    class="modal fade"
    id="remarkModal"
    tabindex="-1"
    aria-labelledby="remarkModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="remarkModalLabel">Add Remark</h5>
                    <small class="text-muted" id="remarkMemberName"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="remarkForm">
                @csrf
                <div class="modal-body">
                    <label for="remarkText" class="form-label fw-semibold">Remark</label>
                    <textarea
                        name="remarks"
                        id="remarkText"
                        rows="4"
                        maxlength="10000"
                        class="form-control"
                        placeholder="Enter an internal remark..."
                        required></textarea>
                    <div class="form-text">Saving creates a staff-attributed entry in the member's remark history.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Save Remark
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================================
    Member Action Confirmation Modal
========================================================= --}}

<div
    class="modal fade"
    id="memberActionModal"
    tabindex="-1"
    aria-labelledby="memberActionModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="memberActionModalLabel">

                    Confirm Action

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>


            <div class="modal-body">

                <div class="d-flex align-items-start">

                    <div
                        class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                        style="width:45px;height:45px;">

                        <i class="bi bi-exclamation-triangle fs-5"></i>

                    </div>

                    <div>

                        <p
                            class="mb-0"
                            id="memberActionModalMessage">

                            Are you sure you want to perform this action?

                        </p>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="memberActionConfirmBtn">

                    Confirm

                </button>

            </div>

        </div>

    </div>

</div>

{{-- =========================================================
     MEMBER ROTATION MODAL
========================================================= --}}

<div
    class="modal fade"
    id="rotationModal"
    tabindex="-1"
    aria-labelledby="rotationModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="rotationModalLabel">
                    Set Member Rotation
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>

            <form
                method="POST"
                id="rotationForm">

                @csrf

                <div class="modal-body">

                    {{-- Member --}}
                    <div class="mb-3">

                        <label
                            for="rotationMemberName"
                            class="form-label">
                            Member
                        </label>

                        <input
                            type="text"
                            id="rotationMemberName"
                            class="form-control"
                            readonly>

                    </div>


                    {{-- Days --}}
                    <div class="mb-3">

                        <label
                            for="rotationDays"
                            class="form-label">
                            Rotation Days
                        </label>

                        <input
                            type="number"
                            name="days"
                            id="rotationDays"
                            class="form-control"
                            min="1"
                            max="365"
                            value="7"
                            required>

                        <small class="text-muted">
                            Number of days until the next rotation.
                        </small>

                    </div>


                    {{-- Time --}}
                    <div class="mb-3">

                        <label
                            for="rotationTime"
                            class="form-label">
                            Rotation Time
                        </label>

                        <input
                            type="time"
                            name="time"
                            id="rotationTime"
                            class="form-control"
                            required>

                    </div>


                    {{-- Preview --}}
                    <div
                        class="alert alert-light border"
                        id="rotationPreview">

                        Next rotation will be calculated after saving.

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>

                        Save Rotation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const remarkModal = document.getElementById('remarkModal');
        const remarkMemberName = document.getElementById('remarkMemberName');
        const remarkForm = document.getElementById('remarkForm');
        const remarkText = document.getElementById('remarkText');

        if (!remarkModal || !remarkForm || !remarkText) {
            return;
        }

        remarkModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            remarkForm.action = button.dataset.remarkAction;
            remarkMemberName.textContent = button.dataset.memberName;
            remarkText.value = '';
        });

        remarkModal.addEventListener('shown.bs.modal', function() {
            remarkText.focus();
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectAll = document.getElementById('select-new-members');
        const boxes = [...document.querySelectorAll('.new-member-checkbox')];
        selectAll?.addEventListener('change', () => boxes.forEach(box => box.checked = selectAll.checked));
        boxes.forEach(box => box.addEventListener('change', () => {
            selectAll.checked = boxes.every(item => item.checked);
            selectAll.indeterminate = boxes.some(item => item.checked) && !selectAll.checked;
        }));
        document.getElementById('assign-new-members')?.addEventListener('submit', event => {
            if (!boxes.some(box => box.checked)) {
                event.preventDefault();
                alert('Please select at least one member to assign staff.');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        let selectedForm = null;

        const modalElement = document.getElementById('memberActionModal');

        const modal = new bootstrap.Modal(modalElement);

        const titleElement = document.getElementById(
            'memberActionModalLabel'
        );

        const messageElement = document.getElementById(
            'memberActionModalMessage'
        );

        const confirmButton = document.getElementById(
            'memberActionConfirmBtn'
        );


        /*
        |--------------------------------------------------------------------------
        | Open Confirmation Modal
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.member-action-form').forEach(function(form) {

            form.addEventListener('submit', function(event) {

                event.preventDefault();

                selectedForm = form;

                const title =
                    form.dataset.confirmTitle ||
                    'Confirm Action';

                const message =
                    form.dataset.confirmMessage ||
                    'Are you sure you want to perform this action?';


                titleElement.textContent = title;

                messageElement.textContent = message;


                modal.show();

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Confirm Action
        |--------------------------------------------------------------------------
        */

        confirmButton.addEventListener('click', function() {

            if (!selectedForm) {
                return;
            }


            /*
            | Prevent double-clicks
            */

            confirmButton.disabled = true;

            confirmButton.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-1"
                role="status"
                aria-hidden="true">
            </span>

            Processing...
        `;


            /*
            | Submit original form
            */

            selectedForm.submit();

        });


        /*
        |--------------------------------------------------------------------------
        | Reset Modal
        |--------------------------------------------------------------------------
        */

        modalElement.addEventListener('hidden.bs.modal', function() {

            selectedForm = null;

            confirmButton.disabled = false;

            confirmButton.innerHTML = 'Confirm';

        });

    });

    document.addEventListener('DOMContentLoaded', function() {

        const rotationModal = document.getElementById('rotationModal');

        const rotationForm = document.getElementById('rotationForm');

        const rotationMemberName =
            document.getElementById('rotationMemberName');

        const rotationDays =
            document.getElementById('rotationDays');

        const rotationTime =
            document.getElementById('rotationTime');

        const rotationPreview =
            document.getElementById('rotationPreview');


        /*
        |--------------------------------------------------------------------------
        | Check Elements
        |--------------------------------------------------------------------------
        */

        if (!rotationModal) {
            console.error('Rotation modal not found.');
            return;
        }

        if (!rotationForm) {
            console.error('Rotation form not found.');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Get Current Time
        |--------------------------------------------------------------------------
        */

        function getCurrentTime() {

            const now = new Date();

            const hours =
                String(now.getHours()).padStart(2, '0');

            const minutes =
                String(now.getMinutes()).padStart(2, '0');

            return `${hours}:${minutes}`;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Rotation Preview
        |--------------------------------------------------------------------------
        */

        function updateRotationPreview() {

            const days =
                parseInt(rotationDays.value, 10);

            const time =
                rotationTime.value;


            if (!days || days < 1 || !time) {

                rotationPreview.innerHTML =
                    'Enter rotation days and time.';

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Next Rotation Date
            |--------------------------------------------------------------------------
            */

            const nextDate = new Date();

            nextDate.setHours(0, 0, 0, 0);

            nextDate.setDate(
                nextDate.getDate() + days
            );


            /*
            |--------------------------------------------------------------------------
            | Format Date
            |--------------------------------------------------------------------------
            */

            const dateString =
                nextDate.toLocaleDateString(
                    'en-IN', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | Format Time
            |--------------------------------------------------------------------------
            */

            const [hours, minutes] =
            time.split(':');


            const displayTime =
                new Date();

            displayTime.setHours(
                parseInt(hours, 10),
                parseInt(minutes, 10),
                0,
                0
            );


            const timeString =
                displayTime.toLocaleTimeString(
                    'en-IN', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | Show Preview
            |--------------------------------------------------------------------------
            */

            rotationPreview.innerHTML = `
            <strong>Next Rotation:</strong>
            ${dateString} at ${timeString}
        `;
        }


        /*
        |--------------------------------------------------------------------------
        | Bootstrap Modal Open
        |--------------------------------------------------------------------------
        */

        rotationModal.addEventListener(
            'show.bs.modal',
            function(event) {

                const button =
                    event.relatedTarget;


                if (!button) {

                    console.error(
                        'Rotation modal opened without a trigger button.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Get Member Information
                |--------------------------------------------------------------------------
                */

                const memberId =
                    button.getAttribute('data-member-id');

                const memberName =
                    button.getAttribute('data-member-name');


                console.log(
                    'Opening rotation for member:',
                    memberId,
                    memberName
                );


                /*
                |--------------------------------------------------------------------------
                | Member Name
                |--------------------------------------------------------------------------
                */

                rotationMemberName.value =
                    memberName || '';


                /*
                |--------------------------------------------------------------------------
                | Form Action
                |--------------------------------------------------------------------------
                */

                if (memberId) {

                    rotationForm.action =
                        `/admin/members/${memberId}/rotation`;

                } else {

                    console.error(
                        'Member ID missing from rotation button.'
                    );

                    rotationForm.action = '';
                }


                /*
                |--------------------------------------------------------------------------
                | Default Days
                |--------------------------------------------------------------------------
                */

                rotationDays.value = 7;


                /*
                |--------------------------------------------------------------------------
                | Default Time
                |--------------------------------------------------------------------------
                */

                rotationTime.value =
                    getCurrentTime();


                /*
                |--------------------------------------------------------------------------
                | Update Preview
                |--------------------------------------------------------------------------
                */

                updateRotationPreview();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Days Changed
        |--------------------------------------------------------------------------
        */

        rotationDays.addEventListener(
            'input',
            updateRotationPreview
        );


        /*
        |--------------------------------------------------------------------------
        | Time Changed
        |--------------------------------------------------------------------------
        */

        rotationTime.addEventListener(
            'change',
            updateRotationPreview
        );


        /*
        |--------------------------------------------------------------------------
        | Also Update When User Types Time
        |--------------------------------------------------------------------------
        */

        rotationTime.addEventListener(
            'input',
            updateRotationPreview
        );

    });
</script>

@endpush
