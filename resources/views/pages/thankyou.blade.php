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
                                    <h1>Thank you!</h1>
                                </div>
                                <div class="content">
                                    <p>
                                        Your subscription will be start in 10 minutes. Thank you for join Credifana.
                                    </p>
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