document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('changePasswordForm');

        form.addEventListener('submit', async function(e) {

            e.preventDefault();

            // Clear previous errors
            document.querySelectorAll('.text-danger').forEach(function(element) {
                element.textContent = '';
            });

            const button = document.querySelector('.update-btn');

            button.disabled = true;
            button.textContent = 'Updating...';

            const formData = new FormData(form);

            try {

                const response = await fetch("{{ route('update-password') }}", {

                    method: "POST",

                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        "Accept": "application/json"
                    },

                    body: formData

                });

                const result = await response.json();

                button.disabled = false;
                button.textContent = "Update Password";

                if (response.ok) {

                    form.reset();

                    showToast(result.message);

                } else if (response.status === 422) {

                    Object.keys(result.errors).forEach(function(key) {

                        const errorElement = document.querySelector('.' + key + '_error');

                        if (errorElement) {
                            errorElement.textContent = result.errors[key][0];
                        }

                    });

                    // Show first validation error as toast
                    const firstError = Object.values(result.errors)[0][0];
                    showToast(firstError, true);

                } else {

                    showToast(result.message || "Something went wrong.", true);

                }

            } catch (error) {

                button.disabled = false;
                button.textContent = "Update Password";

                console.error(error);

                showToast("Unable to connect to the server.", true);

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
    });