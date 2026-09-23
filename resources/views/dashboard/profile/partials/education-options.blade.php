@php
    $selectedEducation = (string) old('education', $member->education);
    $hasSavedOption = $educations->contains(fn ($education) => (string) $education->education === $selectedEducation);
@endphp
<option value="" @selected($selectedEducation === '')>Select</option>
@if ($selectedEducation !== '' && ! $hasSavedOption)
    <option value="{{ $selectedEducation }}" selected>{{ $selectedEducation }}</option>
@endif
@foreach ($educations as $education)
    <option value="{{ $education->education }}" @selected($selectedEducation === (string) $education->education)>{{ $education->education }}</option>
@endforeach
