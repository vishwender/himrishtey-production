
(() => {
    'use strict';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

/* ============================================================
   Validation Helpers
============================================================ */
    
function showError(input, errorElement, message) {

    if (input) {
        input.classList.remove('success');
        input.classList.add('error');
    }

    if (errorElement) {
        errorElement.textContent = message;
    }
}

function clearError(input, errorElement) {

    if (input) {
        input.classList.remove('error');
        input.classList.remove('success');
    }

    if (errorElement) {
        errorElement.textContent = '';
    }
}

function markSuccess(input) {

    if (!input) return;

    input.classList.remove('error');
    input.classList.add('success');
}

/* ============================================================
   Validation Functions
============================================================ */

function isValidName(value) {
    return value.trim().length >= 2;
}

function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
}

function isValidMobile(value) {
    return /^[6-9]\d{9}$/.test(value.trim());
}

function isValidPassword(value) {
    return value.length >= 6;
}

function isValidLoginField(value) {
    // Profile ID formats vary by site. Let the server look up the identifier
    // in the current site's database instead of enforcing a brand prefix here.
    return value.trim().length > 0;
}

function initTabs() {

    const tabs = document.querySelectorAll('.login-tab');
    const panels = document.querySelectorAll('.login-tab-panel');
    const switcher = document.querySelector('.login-tab-switcher');

    function setActiveTab(tabName, updateHash = false) {
        const tab = document.querySelector(`.login-tab[data-tab="${tabName}"]`);
        const panel = document.getElementById(`panel-${tabName}`);

        if (!tab || !panel) return;

        tabs.forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });

        panels.forEach(item => {
            item.classList.remove('active');
            item.hidden = true;
        });

        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');
        panel.hidden = false;
        panel.classList.add('active');
        switcher?.setAttribute('data-active', tabName);

        if (updateHash) {
            const url = new URL(window.location.href);
            url.hash = tabName === 'register' ? 'register' : '';
            window.history.replaceState(null, '', url);
        }
    }

    const tabFromHash = () => window.location.hash.toLowerCase() === '#register'
        ? 'register'
        : 'login';

    tabs.forEach(tab => {

        tab.addEventListener('click', function () {
            setActiveTab(this.dataset.tab, true);
        });

    });

    document.querySelectorAll('.switch-tab-btn').forEach(button => {
        button.addEventListener('click', () => setActiveTab(button.dataset.switch, true));
    });

    window.addEventListener('hashchange', () => setActiveTab(tabFromHash()));
    setActiveTab(tabFromHash());

}

document.addEventListener('click', function (e) {

    const btn = e.target.closest('.input-toggle-pass');

    if (!btn) return;

    const input = document.getElementById(btn.dataset.target);

    if (!input) return;

    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';

    btn.innerHTML = isHidden
        ? '<i data-lucide="eye-off" width="16" height="16"></i>'
        : '<i data-lucide="eye" width="16" height="16"></i>';

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

});

const Login = {

    form: document.getElementById('loginForm'),

    field: document.getElementById('loginField'),

    fieldError: document.getElementById('loginFieldError'),

    password: document.getElementById('loginPassword'),

    passwordError: document.getElementById('loginPasswordError'),

    captcha: document.getElementById('loginCaptcha'),

    captchaError: document.getElementById('loginCaptchaError'),

    submit: document.getElementById('loginSubmitBtn'),

    captchaQuestion: document.getElementById('captchaQuestion'),
    formMessage: document.getElementById('loginFormMessage')

};

function setLoginMessage(message) {
    if (!Login.formMessage) return;

    Login.formMessage.textContent = message || '';
    Login.formMessage.hidden = !message;
}

function validateLogin() {

    let valid = true;

    if (!isValidLoginField(Login.field.value)) {

        showError(
            Login.field,
            Login.fieldError,
            'Enter a valid Profile ID, Email or Mobile Number'
        );

        valid = false;

    } else {

        clearError(Login.field, Login.fieldError);

        markSuccess(Login.field);

    }

    if (!Login.password.value || !isValidPassword(Login.password.value)) {

        showError(
            Login.password,
            Login.passwordError,
            'Password must be at least 6 characters'
        );

        valid = false;

    } else {

        clearError(Login.password, Login.passwordError);

        markSuccess(Login.password);

    }

    if (!Login.captcha.value.trim()) {

        showError(
            Login.captcha,
            Login.captchaError,
            'Please enter the captcha.'
        );

        valid = false;

    } else if (isNaN(Login.captcha.value.trim())) {

        showError(
            Login.captcha,
            Login.captchaError,
            'Captcha must be a number.'
        );

        valid = false;

    } else {

        clearError(Login.captcha, Login.captchaError);

        markSuccess(Login.captcha);

    }

    return valid;

}

async function loginMember() {

    const buttonText = Login.submit.querySelector('.btn-login-text');

    Login.submit.classList.add('loading', 'signing-in');
    Login.submit.disabled = true;

    if (buttonText) {
        buttonText.textContent = 'Signing in...';
    }

    try {

        const response = await fetch(`member-login`, {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'Accept': 'application/json',

                'X-CSRF-TOKEN': csrf

            },

            body: JSON.stringify({

                username: Login.field.value.trim(),

                password: Login.password.value,

                captcha: Login.captcha.value.trim(),

                remember: document.getElementById('rememberMe').checked

            })

        });

        const data = await response.json();

        if (response.ok) {

            setLoginMessage('');
            window.location.href = data.redirect || '/';

            return;

        }

        Login.submit.classList.remove('loading', 'signing-in');
        Login.submit.disabled = false;

        if (buttonText) {
            buttonText.textContent = 'Sign In';
        }

        if (data.captcha && Login.captchaQuestion) {

            Login.captchaQuestion.textContent = data.captcha;

            Login.captcha.value = '';

            showError(
                Login.captcha,
                Login.captchaError,
                'Incorrect captcha. Please try again.'
            );

        }

        const message = data.message || 'Invalid username or password.';
        setLoginMessage(message);
        HimRishteyToast.error(message);

    }

    catch (e) {

        Login.submit.classList.remove('loading', 'signing-in');

        Login.submit.disabled = false;

        if (buttonText) {
            buttonText.textContent = 'Sign In';
        }

        console.error(e);

        setLoginMessage('Something went wrong.');
        HimRishteyToast.error('Something went wrong.');

    }

}
function initLoginForm() {

    if (!Login.form) return;

    Login.password.addEventListener('input', () => {

        clearError(Login.password, Login.passwordError);
        setLoginMessage('');

    });

    Login.field.addEventListener('input', () => setLoginMessage(''));

    Login.form.addEventListener('submit', async function (e) {

        e.preventDefault();

        if (!validateLogin()) {

            return;

        }

        await loginMember();

    });

}

//Register
const Register = {

    form: document.getElementById('registerForm'),

    name: document.getElementById('regName'),
    email: document.getElementById('regEmail'),
    mobile: document.getElementById('regMobile'),
    gender: document.getElementById('regGender'),
    dob: document.getElementById('regDOB'),
    password: document.getElementById('regPassword'),
    captcha: document.getElementById('regCaptcha'),
    terms: document.getElementById('regTerms'),

    submit: document.getElementById('registerSubmitBtn'),

    errors: {

        name: document.getElementById('regNameError'),
        email: document.getElementById('regEmailError'),
        mobile: document.getElementById('regMobileError'),
        gender: document.getElementById('regGenderError'),
        dob: document.getElementById('regDOBError'),
        password: document.getElementById('regPasswordError'),
        captcha: document.getElementById('regCaptchaError'),
        terms: document.getElementById('regTermsError')

    }

};

function validateRegister() {

    let valid = true;

    if (!Register.name.value || !isValidName(Register.name.value)) {

        showError(Register.name, Register.errors.name, 'Please enter your full name');

        valid = false;

    } else {

        clearError(Register.name, Register.errors.name);

        markSuccess(Register.name);

    }

    if (!Register.email.value || !isValidEmail(Register.email.value)) {

        showError(Register.email, Register.errors.email, 'Please enter a valid email.');

        valid = false;

    } else {

        clearError(Register.email, Register.errors.email);

        markSuccess(Register.email);

    }

    if (!Register.mobile.value || !isValidMobile(Register.mobile.value)) {

        showError(Register.mobile, Register.errors.mobile, 'Enter a valid mobile number.');

        valid = false;

    } else {

        clearError(Register.mobile, Register.errors.mobile);

        markSuccess(Register.mobile);

    }

    if (!Register.gender.value) {

        showError(Register.gender, Register.errors.gender, 'Please select gender.');

        valid = false;

    } else {

        clearError(Register.gender, Register.errors.gender);

        markSuccess(Register.gender);

    }

    if (!Register.dob.value) {

        showError(Register.dob, Register.errors.dob, 'Please select date of birth.');

        valid = false;

    } else {

        const dob = new Date(Register.dob.value);

        const age = Math.floor((Date.now() - dob) / 31557600000);

        if (age < 18) {

            showError(Register.dob, Register.errors.dob, 'Minimum age is 18.');

            valid = false;

        } else {

            clearError(Register.dob, Register.errors.dob);

            markSuccess(Register.dob);

        }

    }

    if (!Register.password.value || !isValidPassword(Register.password.value)) {

        showError(Register.password, Register.errors.password, 'Password must be at least 6 characters.');

        valid = false;

    } else {

        clearError(Register.password, Register.errors.password);

        markSuccess(Register.password);

    }

    if (!Register.captcha.value.trim()) {

        showError(Register.captcha, Register.errors.captcha, 'Please enter captcha.');

        valid = false;

    } else if (isNaN(Register.captcha.value)) {

        showError(Register.captcha, Register.errors.captcha, 'Captcha must be numeric.');

        valid = false;

    } else {

        clearError(Register.captcha, Register.errors.captcha);

        markSuccess(Register.captcha);

    }

    if (!Register.terms.checked) {

        showError(null, Register.errors.terms, 'Please accept Terms & Conditions.');

        valid = false;

    } else {

        clearError(null, Register.errors.terms);

    }

    return valid;

}

async function registerMember() {

    Register.submit.classList.add('loading');

    Register.submit.disabled = true;

    const profileCreatedFor = document.querySelector(
        'input[name="profileFor"]:checked'
    ).value;

    try {

        const response = await fetch(`initial-register`, {

            method: 'POST',

            headers: {

                'Content-Type':'application/json',

                'Accept':'application/json',

                'X-CSRF-TOKEN':csrf

            },

            body: JSON.stringify({

                profile_created_for: profileCreatedFor,

                full_name: Register.name.value,

                email: Register.email.value,

                mobile_number: Register.mobile.value,

                gender: Register.gender.value,

                birth_date: Register.dob.value,

                password: Register.password.value,

                captcha: Register.captcha.value

            })

        });

        const data = await response.json();

        Register.submit.classList.remove('loading');

        Register.submit.disabled = false;

        if(data.captcha){

            updateCaptcha(data.captcha);

        }

        if(response.ok){

            HimRishteyToast.success(data.message);

            window.location.href=data.redirect;

            return;

        }

        HimRishteyToast.error(data.message || 'Registration failed.');

    }

    catch(e){

        Register.submit.classList.remove('loading');

        Register.submit.disabled=false;

        console.error(e);

        HimRishteyToast.error('Something went wrong.');

    }

}

function initRegisterForm() {

    if (!Register.form) return;

    if (Register.mobile) {
        Register.mobile.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    if (Register.email) {
        Register.email.addEventListener('blur', () => {
            if (isValidEmail(Register.email.value)) {
                checkMemberExist('email', Register.email.value);
            }
        });
    }

    if (Register.mobile) {
        Register.mobile.addEventListener('blur', () => {
            if (isValidMobile(Register.mobile.value)) {
                checkMemberExist('mobile_number', Register.mobile.value);
            }
        });
    }

    Register.form.addEventListener('submit', async function (e) {

        e.preventDefault();

        if (!validateRegister()) return;

        if (!(await validateUniqueFields())) return;

        await registerMember();

    });

}

async function checkMemberExist(type, value, input, errorElement) {

    if (!value) {
        return false;
    }

    try {

        const response = await fetch(`checkMemberExist`, {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf

            },

            body: JSON.stringify({
                [type]: value
            })

        });

        const data = await response.json();

        if (data.exists) {

            showError(input, errorElement, data.message);

            return true;

        }

        clearError(input, errorElement);

        markSuccess(input);

        return false;

    } catch (e) {

        console.error(e);

        return false;

    }

}

if (Register.email && Register.errors && Register.errors.email) {
    Register.email.addEventListener('blur', async () => {

        if (!isValidEmail(Register.email.value)) return;

        await checkMemberExist(
            'email',
            Register.email.value,
            Register.email,
            Register.errors.email
        );

    });
}

if (Register.mobile && Register.errors && Register.errors.mobile) {
    Register.mobile.addEventListener('blur', async () => {

        if (!isValidMobile(Register.mobile.value)) return;

        await checkMemberExist(
            'mobile_number',
            Register.mobile.value,
            Register.mobile,
            Register.errors.mobile
        );

    });
}

async function validateUniqueFields() {

    if (!Register.form || !Register.email || !Register.mobile || !Register.errors) {
        return true;
    }

    const emailExists = await checkMemberExist(
        'email',
        Register.email.value,
        Register.email,
        Register.errors.email
    );

    const mobileExists = await checkMemberExist(
        'mobile_number',
        Register.mobile.value,
        Register.mobile,
        Register.errors.mobile
    );

    return !(emailExists || mobileExists);

}

const loginOtpSubmitBtn = document.getElementById("loginOtpSubmitBtn");
const loginOtpGroup = document.getElementById("loginOtpGroup");
const loginOtp = document.getElementById("loginOtp");
const loginOtpError = document.getElementById("loginOtpError");
const loginOtpTimer = document.getElementById("loginOtpTimer");
const resendLoginOtpBtn = document.getElementById("resendLoginOtpBtn");

let loginOtpCountdown = null;

if (loginOtpSubmitBtn) {
    loginOtpSubmitBtn.addEventListener("click", async function () {

        const loginField = document.getElementById("loginField");
        const captcha = document.getElementById("loginCaptcha");

        const login = loginField && loginField.value ? loginField.value.trim() : "";
        const captchaValue = captcha && captcha.value ? captcha.value.trim() : "";

        const url = loginOtpSubmitBtn.dataset.url;

        if (loginOtpError) {
            loginOtpError.textContent = "";
        }

        if (!login) {
            const loginFieldError = document.getElementById("loginFieldError");
            if (loginFieldError) loginFieldError.textContent =
                "Please enter your Profile ID, Email or Mobile Number.";

            return;
        }

        if (!captchaValue) {
            const loginCaptchaError = document.getElementById("loginCaptchaError");
            if (loginCaptchaError) loginCaptchaError.textContent =
                "Please enter the captcha answer.";

            return;
        }

        const buttonText = loginOtpSubmitBtn.querySelector(".btn-login-text");
        const loader = loginOtpSubmitBtn.querySelector(".btn-login-loader");

        loginOtpSubmitBtn.disabled = true;

        if (buttonText) {
            buttonText.textContent = "Sending OTP...";
        }

        if (loader) {
            loader.style.display = "inline-block";
        }

        try {

            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            )?.getAttribute("content") || "";

            const response = await fetch(url ,
                {
                    method: "POST",

                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },

                    body: JSON.stringify({
                        login: login,
                        captcha: captchaValue
                    })
                }
            );

            const data = await response.json();

            console.log("OTP response:", data);

            if (!response.ok || data.status !== "success") {
                throw new Error(
                    data.message || "Unable to send OTP."
                );
            }

            if (loginOtpGroup) {
                loginOtpGroup.style.display = "block";
            }

            if (loginOtp) {
                loginOtp.focus();
            }

            if (buttonText) {
                buttonText.textContent = "OTP Sent";
            }

            startLoginOtpTimer(60);

        } catch (error) {

            console.error("OTP Error:", error);

            if (loginOtpError) {
                loginOtpError.textContent = error.message;
            }

            if (buttonText) {
                buttonText.textContent = "Login with OTP";
            }

        } finally {

            if (loader) {
                loader.style.display = "none";
            }

            loginOtpSubmitBtn.disabled = false;
        }
    });
}

if (loginOtp) {
    loginOtp.addEventListener("input", function () {

        this.value = this.value.replace(/\D/g, "");
        console.log(this.value);

        if (this.value.length === 4) {
            verifyLoginOtp(this.value);
        }

    });
}

async function verifyLoginOtp(otp) {
    console.log(otp);

    loginOtpError.textContent = "";

    const buttonText =
        loginOtpSubmitBtn.querySelector(".btn-login-text");

    loginOtpSubmitBtn.disabled = true;

    if (buttonText) {
        buttonText.textContent = "Verifying...";
    }

    try {

        const csrfToken =
            document.querySelector(
                'meta[name="csrf-token"]'
            ).content;

        const response = await fetch(
            `verify-login-otp`,
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },

                body: JSON.stringify({
                    otp: otp
                })
            }
        );

        const data = await response.json();

        console.log("Verify OTP:", data);

        if (!response.ok ||
            data.status !== "success") {

            throw new Error(
                data.message ||
                "Invalid OTP."
            );
        }

        if (buttonText) {
            buttonText.textContent =
                "Login Successful";
        }

        window.location.href = '/home';

    } catch (error) {

        console.error(error);

        loginOtpError.textContent =
            error.message;

        loginOtp.value = "";

        loginOtp.focus();

        loginOtpSubmitBtn.disabled = false;

        if (buttonText) {
            buttonText.textContent =
                "Login with OTP";
        }
    }
}

function startLoginOtpTimer(seconds) {

    clearInterval(loginOtpCountdown);

    let remaining = seconds;

    const timerElement =
        document.getElementById("loginOtpTimer");

    const resendButton =
        document.getElementById("resendLoginOtpBtn");

    if (!timerElement) {
        return;
    }

    if (resendButton) {
        resendButton.style.display = "none";
    }

    timerElement.textContent =
        `Resend OTP in ${remaining}s`;

    loginOtpCountdown = setInterval(function () {

        remaining--;

        if (remaining <= 0) {

            clearInterval(loginOtpCountdown);

            timerElement.textContent = "";

            if (resendButton) {
                resendButton.style.display = "inline-block";
            }

            return;
        }

        timerElement.textContent =
            `Resend OTP in ${remaining}s`;

    }, 1000);
}

document.addEventListener('DOMContentLoaded', () => {

    initTabs();
    initLoginForm();
    initRegisterForm();

});

/* ---- LUCIDE ICONS ---- */
  if (typeof lucide !== 'undefined') lucide.createIcons();
  else window.addEventListener('load', () => { if (typeof lucide !== 'undefined') lucide.createIcons(); });

})();
