@php
    $incomeSelection = (string) ($selected ?? '');
    $incomeOptions = \App\Support\AnnualIncomeOptions::labels();
@endphp
<option value="" @selected($incomeSelection === '')>{{ $placeholder ?? 'Select annual income' }}</option>
@if ($incomeSelection !== '' && !in_array($incomeSelection, $incomeOptions, true))
    <option value="{{ $incomeSelection }}" selected>{{ $incomeSelection }} (previously saved)</option>
@endif
@foreach ($incomeOptions as $incomeOption)
    <option value="{{ $incomeOption }}" @selected($incomeSelection === $incomeOption)>{{ $incomeOption }}</option>
@endforeach
