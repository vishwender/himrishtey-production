const sendOtpBtn = document.getElementById('sendVerifyOtpBtn');

const verifyOtpBtn = document.getElementById('verifyOtpBtn');
const resendOtpBtn = document.getElementById('resendOtpBtn');
const otpSection = document.getElementById('otpSection');
const phoneInput = document.getElementById('verifyPhoneNumber');
const otpInput = document.getElementById('otp');
const phoneError = document.getElementById('phoneError');
const otpError = document.getElementById('otpError');
const otpTimer = document.getElementById('otpTimer');


        let timerInterval = null;


        /* =========================================================
           VALIDATE PHONE
        ========================================================== */

        function validatePhone() {

            const phone =
                phoneInput.value.trim();

            phoneError.textContent = '';

            if (!/^[6-9]\d{9}$/.test(phone)) {

                phoneError.textContent =
                    'Please enter a valid 10 digit mobile number.';

                return false;
            }

            return true;
        }


        /* =========================================================
           START OTP TIMER
        ========================================================== */

        function startOtpTimer(seconds = 300) {

            clearInterval(timerInterval);

            resendOtpBtn.disabled = true;

            timerInterval =
                setInterval(function() {

                    const minutes =
                        Math.floor(seconds / 60);

                    const remainingSeconds =
                        seconds % 60;

                    otpTimer.textContent =
                        String(minutes).padStart(2, '0') +
                        ':' +
                        String(remainingSeconds).padStart(2, '0');


                    if (seconds <= 0) {

                        clearInterval(timerInterval);

                        resendOtpBtn.disabled = false;

                        otpTimer.textContent =
                            '00:00';

                        return;
                    }

                    seconds--;

                }, 1000);
        }


        /* =========================================================
           SEND OTP
        ========================================================== */

        sendOtpBtn.addEventListener(
            'click',
            async function() {

                if (!validatePhone()) {
                    return;
                }

                const phone =
                    phoneInput.value.trim();

                sendOtpBtn.disabled = true;

                sendOtpBtn.textContent =
                    'Sending...';


                try {

                    const response =
                        await fetch(
                            '/send-otp', {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',

                                    'Accept': 'application/json',

                                    'X-CSRF-TOKEN': document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .content
                                },

                                body: JSON.stringify({
                                    phone: phone
                                })
                            }
                        );


                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Unable to send OTP.'
                        );
                    }


                    otpSection.classList.add('show');

                    sendOtpBtn.textContent =
                        'OTP Sent';

                    otpInput.focus();

                    startOtpTimer(300);


                } catch (error) {

                    phoneError.textContent =
                        error.message;

                    sendOtpBtn.disabled = false;

                    sendOtpBtn.textContent =
                        'Send OTP';
                }

            }
        );


        /* =========================================================
           RESEND OTP
        ========================================================== */

        resendOtpBtn.addEventListener(
            'click',
            function() {

                sendOtpBtn.click();

            }
        );


        /* =========================================================
           VERIFY OTP
        ========================================================== */

        verifyOtpBtn.addEventListener(
            'click',
            async function() {

                otpError.textContent = '';

                const otp =
                    otpInput.value.trim();


                if (!/^\d{4}$/.test(otp)) {

                    otpError.textContent =
                        'Please enter the 4 digit OTP.';

                    return;
                }


                verifyOtpBtn.disabled = true;

                verifyOtpBtn.textContent =
                    'Verifying...';


                try {

                    const response =
                        await fetch(
                            '/verify-account-request', {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',

                                    'Accept': 'application/json',

                                    'X-CSRF-TOKEN': document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .content
                                },

                                body: JSON.stringify({
                                    phone: phoneInput.value.trim(),

                                    otp: otp
                                })
                            }
                        );


                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Invalid OTP.'
                        );
                    }


                    window.location.href =
                        data.redirect ||
                        '/home';


                } catch (error) {

                    otpError.textContent =
                        error.message;

                    verifyOtpBtn.disabled = false;

                    verifyOtpBtn.textContent =
                        'Verify OTP';
                }

            }
        );