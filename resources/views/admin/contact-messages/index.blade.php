@extends('admin.layout')
@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')
@section('content')
<div class="card shadow-sm">
    <div class="card-header"><h1 class="h3 mb-1">Contact Us inbox</h1><p class="text-muted mb-0">Messages received for the selected site.</p></div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-7"><label for="search" class="form-label">Search</label><input id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, email or subject"></div>
            <div class="col-md-3"><label for="status" class="form-label">Status</label><select id="status" name="status" class="form-select">@foreach(['all' => 'All messages', 'unread' => 'Unread', 'read' => 'Read'] as $value => $label)<option value="{{ $value }}" @selected(request('status', 'all') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary">Filter</button></div>
        </form>
        <form id="bulk-delete-messages" method="POST" action="{{ route('admin.contact-messages.bulk-destroy') }}" onsubmit="return confirm('Permanently delete the selected contact messages? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button id="delete-selected-messages" class="btn btn-danger mb-3" disabled>Delete selected</button>
        </form>
        <div class="table-responsive"><table class="table align-middle">
            <thead><tr><th><input type="checkbox" id="select-all-messages" class="form-check-input" aria-label="Select all messages on this page"></th><th>Status</th><th>Sender</th><th>Subject</th><th>Received</th><th></th></tr></thead>
            <tbody>@forelse($messages as $message)
            <tr class="{{ $message->read_at ? '' : 'fw-semibold' }}">
                <td><input type="checkbox" name="ids[]" value="{{ $message->id }}" form="bulk-delete-messages" class="form-check-input message-selection" aria-label="Select message from {{ $message->name }}: {{ $message->subject }}"></td>
                <td><span class="badge {{ $message->read_at ? 'bg-secondary' : 'bg-primary' }}">{{ $message->read_at ? 'Read' : 'New' }}</span></td>
                <td>{{ $message->name }}<div class="small text-muted">{{ $message->email }}</div></td>
                <td>{{ $message->subject }}</td><td>{{ $message->created_at->format('d M Y, h:i A') }}</td>
                <td><a href="{{ route('admin.contact-messages.show', $message->id) }}" class="btn btn-sm btn-outline-primary">View message</a>
                    <form method="POST" action="{{ route('admin.contact-messages.destroy', $message->id) }}" class="d-inline" onsubmit="return confirm('Permanently delete this contact message? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty<tr><td colspan="6" class="text-center text-muted py-5">No contact messages found.</td></tr>@endforelse</tbody>
        </table></div>
        {{ $messages->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const selectAll = document.getElementById('select-all-messages');
    const selections = [...document.querySelectorAll('.message-selection')];
    const deleteButton = document.getElementById('delete-selected-messages');
    function updateSelection() {
        const count = selections.filter(input => input.checked).length;
        deleteButton.disabled = count === 0;
        deleteButton.textContent = count ? `Delete selected (${count})` : 'Delete selected';
        selectAll.checked = selections.length > 0 && count === selections.length;
        selectAll.indeterminate = count > 0 && count < selections.length;
        selectAll.disabled = selections.length === 0;
    }
    selectAll.addEventListener('change', () => {
        selections.forEach(input => input.checked = selectAll.checked);
        updateSelection();
    });
    selections.forEach(input => input.addEventListener('change', updateSelection));
    updateSelection();
})();
</script>
@endpush
