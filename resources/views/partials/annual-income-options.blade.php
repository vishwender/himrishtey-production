@php
    $incomeSelection = (string) ($selected ?? '');
    $incomeOptions = \App\Support\AnnualIncomeOptions::labels();
    if (isset($maxWholeLakh)) {
        $incomeOptions = array_values(array_filter($incomeOptions, static function ($option) use ($maxWholeLakh) {
            return !preg_match('/^\d+-(\d+) lakhs$/', $option, $match) || (int) $match[1] <= $maxWholeLakh;
        }));
    }
@endphp
<option value="" @selected($incomeSelection === '')>{{ $placeholder ?? 'Select annual income' }}</option>
@if ($incomeSelection !== '' && !in_array($incomeSelection, $incomeOptions, true))
    <option value="{{ $incomeSelection }}" selected>{{ $incomeSelection }} (previously saved)</option>
@endif
@foreach ($incomeOptions as $incomeOption)
    @php
        $incomeLabel = ($wholeLakhLabels ?? false) && $incomeOption === 'more than 50 lakhs'
            ? 'More than 50 lakhs'
            : $incomeOption;
        if (($wholeLakhLabels ?? false) && preg_match('/^\d+-(\d+) lakhs$/', $incomeOption, $incomeMatch)) {
            $amount = (int) $incomeMatch[1];
            $incomeLabel = $amount === 1 ? '1 lakh' : $amount.' lakhs';
        }
    @endphp
    <option value="{{ $incomeOption }}" @selected($incomeSelection === $incomeOption)>{{ $incomeLabel }}</option>
@endforeach
