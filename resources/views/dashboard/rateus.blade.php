@extends('layouts.dashboard')

@section('title', 'Rate Us - HimRishtey')

@section('content')
<section class="container py-4">
    <div class="card shadow-sm mx-auto" style="max-width: 680px;">
        <div class="card-body p-4">
            <h1 class="h3 mb-2">Rate your experience</h1>
            <p class="text-muted">Your feedback helps us improve HimRishtey.</p>
            <form id="rating-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="stars">Rating</label>
                    <select class="form-select" id="stars" name="stars" required>
                        <option value="">Choose a rating</option>
                        @for ($rating = 1; $rating <= 5; $rating++)
                            <option value="{{ $rating }}">{{ $rating }} star{{ $rating > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="feedback">Feedback (optional)</label>
                    <textarea class="form-control" id="feedback" name="feedback" rows="5" maxlength="1000"></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Submit rating</button>
                <p class="mt-3 mb-0" id="rating-message" aria-live="polite"></p>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.getElementById('rating-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const response = await fetch('{{ route('user-rate') }}', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value },
        body: new FormData(form),
    });
    const result = await response.json();
    if (result.redirect) {
        window.location.assign(result.url);
        return;
    }
    document.getElementById('rating-message').textContent = result.message || 'Unable to submit your rating.';
});
</script>
@endsection
