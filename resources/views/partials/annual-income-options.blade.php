@php
    $incomeSelection = (string) ($selected ?? '');
    $incomeOptions = \App\Support\AnnualIncomeOptions::labels();
@endphp
<option value="" @selected($incomeSelection === '')>{{ $placeholder ?? 'Select annual income' }}</option>
@if ($incomeSelection !== '' && !in_array($incomeSelection, $incomeOptions, true))
    <option value="{{ $incomeSelection }}" selected>{{ $incomeSelection }} (previously saved)</option>
@endif
@foreach ($incomeOptions as $incomeOption)
    @php
        $incomeLabel = ($wholeLakhLabels ?? false) && $incomeOption === 'more than 50 lakhs'
            ? 'more than 50 Lakhs'
            : $incomeOption;
        if (($wholeLakhLabels ?? false) && preg_match('/^\d+-(\d+) lakhs$/', $incomeOption, $incomeMatch)) {
            $amount = (int) $incomeMatch[1];
            $incomeLabel = $amount === 1 ? '1 lakh' : $amount.' lakhs';
        }
    @endphp
    <option value="{{ $incomeOption }}" @selected($incomeSelection === $incomeOption)>{{ $incomeLabel }}</option>
@endforeach
