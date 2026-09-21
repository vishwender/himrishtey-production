const radios = document.querySelectorAll('input[name="reason"]');
    const otherBox = document.getElementById('otherReasonWrapper');

    radios.forEach(radio => {

        radio.addEventListener('change', function() {

            if (this.value === 'Other') {
                otherBox.style.display = 'block';
            } else {
                otherBox.style.display = 'none';
            }

        });

    });


    document.getElementById('deleteProfileForm').addEventListener('submit', async function(e) {

        e.preventDefault();

        if (!confirm('Are you sure you want to delete your profile?')) {
            return;
        }

        const formData = new FormData(this);
       

        try {

            const response = await fetch("/destroy", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
             console.log(response.status);
             console.log(data);

            if (response.ok) {

                showToast(data.message);

                // setTimeout(() => {
                //     window.location.href = "{{ route('member.login') }}";
                // }, 1500);

            } else {

                showToast(data.message || 'Unable to delete profile.', true);

            }

        } catch (error) {

            showToast('Something went wrong.', true);

        }

    });

    function showToast(message, isError = false) {
        const toast = document.getElementById('epSuccessToast');
        const msgEl = document.getElementById('epToastMsg');
        if (!toast || !msgEl) return;

        msgEl.textContent = message;
        toast.style.background = isError ?
            'var(--color-error, #a12c7b)' :
            'var(--color-text)';

        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3500);
    }