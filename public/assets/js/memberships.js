const callbackBtn = document.getElementById('callbackBtn');
const timer = document.getElementById('timer');

const callbackUrl = callbackBtn.dataset.url;
const callbackStatusUrl = callbackBtn.dataset.statusUrl;

let countdown = null;


/*
|--------------------------------------------------------------------------
| Start countdown
|--------------------------------------------------------------------------
*/

function startCallbackTimer(timeLeft) {

    // Prevent duplicate timers
    if (countdown) {
        clearInterval(countdown);
        countdown = null;
    }

    callbackBtn.disabled = true;
    callbackBtn.innerHTML = 'Callback Requested';

    updateTimer(timeLeft);

    countdown = setInterval(function () {

        timeLeft--;

        updateTimer(timeLeft);

        if (timeLeft <= 0) {

            clearInterval(countdown);

            countdown = null;

            callbackBtn.disabled = false;
            callbackBtn.innerHTML = 'Request a Callback';

            timer.innerHTML = '00:00';
        }

    }, 1000);
}


/*
|--------------------------------------------------------------------------
| Update timer
|--------------------------------------------------------------------------
*/

function updateTimer(timeLeft) {

    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;

    timer.innerHTML =
        String(minutes).padStart(2, '0') +
        ':' +
        String(seconds).padStart(2, '0');
}


/*
|--------------------------------------------------------------------------
| Check callback status when page loads
|--------------------------------------------------------------------------
*/

async function checkCallbackStatus() {

    try {

        const response = await fetch(callbackStatusUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        console.log('Callback status:', data);

        if (
            data.status === 'cooldown' &&
            data.remaining > 0
        ) {
            startCallbackTimer(data.remaining);
        } else {
            callbackBtn.disabled = false;
            callbackBtn.innerHTML = 'Request a Callback';
            timer.innerHTML = '00:00';
        }

    } catch (error) {

        console.error(
            'Unable to check callback status:',
            error
        );

    }
}


/*
|--------------------------------------------------------------------------
| Callback button
|--------------------------------------------------------------------------
*/

callbackBtn.addEventListener('click', async function () {

    if (countdown) {
        return;
    }

    const button = this;
    

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    )?.getAttribute('content');

    button.disabled = true;
    button.innerHTML = 'Sending...';

    try {

        const response = await fetch(callbackStatusUrl, {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();

        console.log(
            'Callback API response:',
            data
        );


        /*
        |--------------------------------------------------------------------------
        | API failed / cooldown already active
        |--------------------------------------------------------------------------
        */

        if (
            !response.ok ||
            data.status !== 'success'
        ) {

            // If server says cooldown is still active
            if (
                data.remaining &&
                data.remaining > 0
            ) {

                startCallbackTimer(
                    data.remaining
                );

                return;
            }

            throw new Error(
                data.message ||
                'Unable to send callback request.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SMS successfully sent
        |--------------------------------------------------------------------------
        */

        HimRishteyToast.success(data.message);

        startCallbackTimer(
            data.remaining || 600
        );

    } catch (error) {

        console.error(
            'Callback request error:',
            error
        );

        HimRishteyToast.error(
            error.message ||
            'Unable to request callback.'
        );

        button.disabled = false;
        button.innerHTML = 'Request a Callback';
    }

});


/*
|--------------------------------------------------------------------------
| Check status immediately when page loads
|--------------------------------------------------------------------------
*/

checkCallbackStatus();
