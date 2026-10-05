@extends('layouts.dashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/membership-checkout.css') }}?v={{ filemtime(public_path('assets/css/membership-checkout.css')) }}">
@endsection

@section('content')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<div class="membership-checkout">

    <div class="checkout-container">

        <!-- Page Heading -->

        <div class="checkout-heading">

            <h1>Complete Your <span>Payment</span></h1>

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
                            {{ $siteName }}.
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

            name: @js($siteName),

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
