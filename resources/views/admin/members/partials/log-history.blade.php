<div class="list-group list-group-flush">
    @forelse($entries as $entry)
    <div class="list-group-item px-0">
        <div class="d-flex justify-content-between gap-3 flex-wrap">
            <span class="fw-semibold">{{ $entry['type'] === 'assigned_to' ? 'Assigned To' : $entry['type'] }}</span>
            <small class="text-muted">{{ $entry['date'] ?: 'Date unavailable' }}</small>
        </div>
        <div style="white-space: pre-wrap; overflow-wrap: anywhere;">{{ $entry['text'] }}</div>
        @if(!empty($entry['staff']))
        <small class="text-muted">{{ $entry['type'] === 'Rotation Added' ? 'Rotation added by:' : 'Recorded by:' }} {{ $entry['staff'] }}</small>
        @endif
    </div>
    @empty
    <p class="text-muted mb-0">{{ $emptyMessage }}</p>
    @endforelse
</div>
