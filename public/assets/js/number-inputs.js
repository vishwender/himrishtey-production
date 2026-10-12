(() => {
    const isNumber = input => input instanceof HTMLInputElement && input.type === 'number';
    const constrain = input => {
        if (!isNumber(input)) return;
        if (input.min === '' || Number(input.min) < 0) input.min = '0';
    };
    document.querySelectorAll('input[type="number"]').forEach(constrain);
    document.addEventListener('focusin', event => constrain(event.target));
    document.addEventListener('keydown', event => {
        if (isNumber(event.target) && event.key === '-') event.preventDefault();
    });
    document.addEventListener('input', event => {
        const input = event.target;
        if (!isNumber(input)) return;
        constrain(input);
        if (input.value !== '' && Number(input.value) < 0) input.value = '';
    });
    document.addEventListener('paste', event => {
        if (isNumber(event.target) && /-/.test(event.clipboardData?.getData('text') ?? '')) event.preventDefault();
    });
})();
