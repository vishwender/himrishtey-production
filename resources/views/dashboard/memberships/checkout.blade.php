@extends('layouts.dashboard')

@section('content')

<style>
    :root {
        --app-primary: #6d4aff;
        --app-primary-dark: #5638d8;
        --app-ink: #202034;
        --app-muted: #77778c;
        --app-surface: #ffffff;
        --app-canvas: #f7f6fc;
        --app-border: #ebe9f4;
        --app-success: #22a06b;
    }

    .membership-checkout {
        min-height: calc(100vh - 80px);
        background:
            radial-gradient(circle at 10% 10%,
                rgba(109, 74, 255, .08),
                transparent 28%),
            radial-gradient(circle at 90% 90%,
                rgba(109, 74, 255, .06),
                transparent 30%),
            var(--app-canvas);

        padding: 55px 20px 70px;
    }

    .checkout-container {
        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
    }

    /* ---------------------------------
       Header
    --------------------------------- */

    .checkout-heading {
        text-align: center;
        margin-bottom: 38px;
    }

    .checkout-heading h1 {
        margin: 0 0 8px;

        font-family: "Outfit", sans-serif;
        font-size: 34px;
        font-weight: 700;

        color: var(--app-ink);
    }

    .checkout-heading p {
        margin: 0;

        font-size: 15px;
        color: var(--app-muted);
    }

    /* ---------------------------------
       Main Grid
    --------------------------------- */

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 390px;
        gap: 25px;
        align-items: start;
    }

    /* ---------------------------------
       Card
    --------------------------------- */

    .checkout-card {
        background: var(--app-surface);

        border: 1px solid var(--app-border);
        border-radius: 22px;

        box-shadow:
            0 15px 45px rgba(35, 25, 75, .07);

        overflow: hidden;
    }

    .card-header {
        padding: 25px 28px;

        border-bottom: 1px solid var(--app-border);
    }

    .card-header h2 {
        margin: 0;

        font-family: "Outfit", sans-serif;
        font-size: 21px;
        font-weight: 600;

        color: var(--app-ink);
    }

    .card-header p {
        margin: 5px 0 0;

        font-size: 13px;
        color: var(--app-muted);
    }

    .card-body {
        padding: 28px;
    }

    /* ---------------------------------
       Plan Card
    --------------------------------- */

    .plan-card {
        position: relative;

        padding: 25px;

        border-radius: 18px;

        background:
            linear-gradient(135deg,
                #f8f6ff 0%,
                #ffffff 100%);

        border: 1px solid #e6e0ff;
    }

    .plan-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;

        gap: 20px;
    }

    .plan-label {
        display: inline-flex;
        align-items: center;

        padding: 6px 11px;

        border-radius: 20px;

        background: rgba(109, 74, 255, .1);

        color: var(--app-primary);

        font-size: 11px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .7px;

        margin-bottom: 10px;
    }

    .plan-name {
        margin: 0;

        font-family: "Outfit", sans-serif;

        font-size: 25px;
        font-weight: 600;

        color: var(--app-ink);
    }

    .plan-price {
        text-align: right;
        white-space: nowrap;
    }

    .plan-price small {
        display: block;

        font-size: 12px;
        color: var(--app-muted);

        margin-bottom: 2px;
    }

    .plan-price strong {
        font-family: "Outfit", sans-serif;

        font-size: 28px;
        font-weight: 700;

        color: var(--app-primary);
    }

    /* ---------------------------------
       Plan Details
    --------------------------------- */

    .plan-description {
        margin-top: 20px;

        color: var(--app-muted);

        font-size: 14px;
        line-height: 1.65;
    }

    .features {
        margin: 24px 0 0;
        padding: 0;

        list-style: none;

        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 13px;
    }

    .features li {
        display: flex;
        align-items: center;
        gap: 9px;

        color: #4e4e62;

        font-size: 13px;
    }

    .feature-icon {
        width: 21px;
        height: 21px;

        flex: 0 0 21px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #eaf9f2;
        color: var(--app-success);

        font-size: 11px;
        font-weight: 700;
    }

    /* ---------------------------------
       Order Summary
    --------------------------------- */

    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 13px 0;

        font-size: 14px;
        color: var(--app-muted);

        border-bottom: 1px solid #f0eef6;
    }

    .summary-row:last-child {
        border-bottom: 0;
    }

    .summary-row strong {
        color: var(--app-ink);
        font-weight: 600;
    }

    .summary-total {
        margin-top: 8px;
        padding-top: 19px;

        border-top: 1px solid var(--app-border);

        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-total span {
        font-size: 15px;
        font-weight: 600;

        color: var(--app-ink);
    }

    .summary-total strong {
        font-family: "Outfit", sans-serif;

        font-size: 27px;
        font-weight: 700;

        color: var(--app-primary);
    }

    /* ---------------------------------
       Payment Button
    --------------------------------- */

    .payment-section {
        margin-top: 25px;
    }

    #pay-button {
        width: 100%;
        height: 54px;

        border: 0;
        border-radius: 13px;

        background: var(--app-primary);
        color: #fff;

        font-family: "DM Sans", sans-serif;

        font-size: 15px;
        font-weight: 600;

        cursor: pointer;

        box-shadow:
            0 10px 24px rgba(109, 74, 255, .25);

        transition:
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    #pay-button:hover {
        background: var(--app-primary-dark);

        transform: translateY(-2px);

        box-shadow:
            0 14px 28px rgba(109, 74, 255, .3);
    }

    #pay-button:active {
        transform: translateY(0);
    }

    /* ---------------------------------
       Secure Payment
    --------------------------------- */

    .secure-payment {
        margin-top: 17px;

        text-align: center;

        font-size: 12px;

        color: var(--app-muted);
    }

    .secure-payment strong {
        color: #4f4f63;
    }

    .secure-icon {
        margin-right: 4px;
    }

    /* ---------------------------------
       Test Mode
    --------------------------------- */

    .test-mode {
        margin-top: 14px;

        padding: 10px 13px;

        border-radius: 10px;

        background: #fff9e8;
        border: 1px solid #f5e6b5;

        color: #806b2e;

        font-size: 11px;

        text-align: center;
    }

    /* ---------------------------------
       Responsive
    --------------------------------- */

    @media (max-width: 850px) {

        .checkout-grid {
            grid-template-columns: 1fr;
        }

        .checkout-container {
            max-width: 650px;
        }
    }

    @media (max-width: 600px) {

        .membership-checkout {
            padding: 35px 15px 50px;
        }

        .checkout-heading {
            margin-bottom: 25px;
        }

        .checkout-heading h1 {
            font-size: 28px;
        }

        .checkout-card {
            border-radius: 18px;
        }

        .card-header,
        .card-body {
            padding: 21px;
        }

        .plan-top {
            flex-direction: column;
        }

        .plan-price {
            text-align: left;
        }

        .features {
            grid-template-columns: 1fr;
        }
    }
</style>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<div class="membership-checkout">

    <div class="checkout-container">

        <!-- Page Heading -->

        <div class="checkout-heading">

            <h1>Complete Your Payment</h1>

            <p>
                Upgrade your membership and unlock more possibilities.
            </p>

        </div>


        <!-- Checkout Grid -->

        <div class="checkout-grid">


            <!-- LEFT : PLAN -->

            <div class="checkout-card">

                <div class="card-header">

                    <h2>Your Membership</h2>

                    <p>
                        You're just one step away from activating your plan.
                    </p>

                </div>


                <div class="card-body">

                    <div class="plan-card">

                        <div class="plan-top">

                            <div>

                                <div class="plan-label">
                                    Selected Plan
                                </div>

                                <h3 class="plan-name">
                                    {{ $plan->name }}
                                </h3>

                            </div>


                            <div class="plan-price">

                                <small>
                                    Total
                                </small>

                                <strong>
                                    ₹{{ number_format($plan->final_cost, 2) }}
                                </strong>

                            </div>

                        </div>


                        @if(!empty($plan->description))

                        <div class="plan-description">
                            {{ $plan->description }}
                        </div>

                        @else

                        <div class="plan-description">
                            Enjoy premium membership features and
                            connect with more suitable profiles on
                            Himrishtey.
                        </div>

                        @endif


                        <ul class="features">

                            <li>
                                <span class="feature-icon">✓</span>
                                Premium Membership
                            </li>

                            <li>
                                <span class="feature-icon">✓</span>
                                More Profile Visibility
                            </li>

                            <li>
                                <span class="feature-icon">✓</span>
                                Connect with Members
                            </li>

                            <li>
                                <span class="feature-icon">✓</span>
                                Membership Benefits
                            </li>

                        </ul>

                    </div>

                </div>

            </div>


            <!-- RIGHT : SUMMARY -->

            <div class="checkout-card">

                <div class="card-header">

                    <h2>Order Summary</h2>

                    <p>
                        Review your payment before continuing.
                    </p>

                </div>


                <div class="card-body">

                    <div class="summary-row">

                        <span>
                            Membership
                        </span>

                        <strong>
                            {{ $plan->name }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Plan Amount
                        </span>

                        <strong>
                            ₹{{ number_format($plan->final_cost, 2) }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Taxes
                        </span>

                        <strong>
                            Included
                        </strong>

                    </div>


                    <div class="summary-total">

                        <span>
                            Total Payable
                        </span>

                        <strong>
                            ₹{{ number_format($plan->final_cost, 2) }}
                        </strong>

                    </div>


                    <!-- Payment -->

                    <div class="payment-section">

                        <button id="pay-button">

                            Pay ₹{{ number_format($plan->final_cost, 2) }}

                        </button>

                    </div>


                    <div class="secure-payment">

                        <span class="secure-icon">🔒</span>

                        Secure payment powered by
                        <strong>Razorpay</strong>

                    </div>


                    <div class="test-mode">

                        Test Mode — No real money will be charged.

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    document.getElementById('pay-button').onclick = function() {

        const button = this;

        button.disabled = true;

        button.innerHTML = 'Opening Secure Checkout...';


        const options = {

            key: "{{ $razor_key }}",

            amount: "{{ (int) round($plan->final_cost * 100) }}",

            currency: "INR",

            name: "Himrishtey",

            description: "{{ $plan->name }}",

            order_id: "{{ $order_id }}",


            handler: function(response) {

                console.log('Razorpay Response:', response);


                const form = document.createElement('form');

                form.method = 'POST';

                form.action = "{{ route('membership.verify') }}";


                const fields = {

                    '_token': "{{ csrf_token() }}",

                    'razorpay_order_id': response.razorpay_order_id,

                    'razorpay_payment_id': response.razorpay_payment_id,

                    'razorpay_signature': response.razorpay_signature,

                    'plan_id': "{{ $plan->id }}"

                };


                Object.keys(fields).forEach(function(key) {

                    const input =
                        document.createElement('input');

                    input.type = 'hidden';

                    input.name = key;

                    input.value = fields[key];

                    form.appendChild(input);

                });


                document.body.appendChild(form);

                form.submit();

            },


            modal: {

                ondismiss: function() {

                    button.disabled = false;

                    button.innerHTML =
                        'Pay ₹{{ number_format($plan->final_cost, 2) }}';

                }

            },


            theme: {

                color: "#6d4aff"

            }

        };


        const rzp = new Razorpay(options);


        rzp.on('payment.failed', function(response) {

            console.log(
                'Payment failed:',
                response.error
            );

            window.location.href =
                "{{ route('membership.failed') }}";

        });


        rzp.open();

    };
</script>

@endsection