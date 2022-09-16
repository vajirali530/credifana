@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
@endsection

@section('content')

    <section class="contact-hero-section">
        <div class="container-lg">
            <div class="hero-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="right-block">
                            <div class="top-content">
                                <div class="heading">
                                    <h1>Billing at Credifana</h1>
                                </div>
                                <div class="content">
                                    <p>
                                        We appreciate your interest in credifana. Have questions, Get in touch now!
                                    </p>
                                </div>
                            </div>
                            <div class="card-deck row mb-3 justify-content-around text-center">
                                <div class="card col-md-3 mb-4 box-shadow" style="padding:0px;">
                                    <div class="card-header">
                                        <h4 class="my-0 font-weight-normal">Silver</h4>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="card-title pricing-card-title">$7 <small class="text-muted">/ mo</small></h1>
                                        <ul class="list-unstyled mt-3 mb-4">
                                        <li>10 users included</li>
                                        <li>2 GB of storage</li>
                                        <li>Email support</li>
                                        <li>Help center access</li>
                                        </ul>
                                        <!-- price_1LgQLyEviaLTUto6x79f81QI -->
                                        <button type="button" class="btn btn-lg btn-block btn-primary" data-plan="price_1LhoICB78DxZIiIaf1EvFWhI">Get started</button>
                                    </div>
                                </div>
                                <div class="card col-md-3 mb-4 box-shadow" style="padding:0px;">
                                    <div class="card-header">
                                        <h4 class="my-0 font-weight-normal">Gold</h4>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="card-title pricing-card-title">$15 <small class="text-muted">/ mo</small></h1>
                                        <ul class="list-unstyled mt-3 mb-4">
                                        <li>20 users included</li>
                                        <li>10 GB of storage</li>
                                        <li>Priority email support</li>
                                        <li>Help center access</li>
                                        </ul>
                                        <button type="button" class="btn btn-lg btn-block btn-primary" data-plan="price_1LgQMhEviaLTUto6TnUIYIMF">Get started</button>
                                    </div>
                                </div>
                                <div class="card col-md-3 mb-4 box-shadow" style="padding:0px;">
                                    <div class="card-header">
                                        <h4 class="my-0 font-weight-normal">Platinum</h4>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="card-title pricing-card-title">$29 <small class="text-muted">/ mo</small></h1>
                                        <ul class="list-unstyled mt-3 mb-4">
                                        <li>30 users included</li>
                                        <li>15 GB of storage</li>
                                        <li>Phone and email support</li>
                                        <li>Help center access</li>
                                        </ul>
                                            <button type="button" class="btn btn-lg btn-block btn-primary" data-plan="price_1LgQNCEviaLTUto63O3WZ6FP">Get started</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var stripe = Stripe('{{ env("STRIPE_PUBLIC_KEY") }}');
            
            $(".btn-primary").click(function (e) { 
                e.preventDefault();
                var selectedPlan = $(this).data('plan');

                fetch("{{ route('billing-checkout') }}", {
                        method: 'POST',
                        body: JSON.stringify({
                            selectedPlan: selectedPlan,
                            _token : "{{ csrf_token() }}"
                        }),
                        headers: {
                            'Content-type': 'application/json; charset=UTF-8'
                        }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(session) {
                    return stripe.redirectToCheckout({
                        sessionId: session.id
                    });
                })
                .then(function(result) {
                    // If `redirectToCheckout` fails due to a browser or network
                    // error, you should display the localized error message to your
                    // customer using `error.message`.
                    if (result.error) {
                        alert(result.error.message);
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
            });
        });
    </script>
    
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/main.js') }}"></script>
@endsection