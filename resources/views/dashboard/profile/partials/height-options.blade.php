@php
    $selectedHeight = (string) old('height', $member->height);
    $hasSavedOption = $heights->contains(fn ($height) => (string) ($height->height_value ?? $height->height) === $selectedHeight);
@endphp
<option value="" @selected($selectedHeight === '')>Select Height</option>
@if ($selectedHeight !== '' && ! $hasSavedOption)
    <option value="{{ $selectedHeight }}" selected>{{ \App\Support\HeightFormatter::format($selectedHeight) }}</option>
@endif
@foreach ($heights as $height)
    @php($heightValue = (string) ($height->height_value ?? $height->height))
    <option value="{{ $heightValue }}" @selected($selectedHeight === $heightValue)>{{ \App\Support\HeightFormatter::format($height->height) }}</option>
@endforeach
