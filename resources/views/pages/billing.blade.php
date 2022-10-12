@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
@endsection

@section('content')

    <section class="contact-hero-section">
        <div class="container-lg">
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-check-circle text-success mr-2"></i> {{ Session::get('error') }}
                </div>
            @endif
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
                                        <h4 class="my-0 font-weight-normal">Basic</h4>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                        <div class="w-100">
                                            <h1 class="card-title pricing-card-title">Free<small class="text-muted"></small></h1>
                                            <ul class="list-unstyled mt-3 mb-4 planPoints">
                                            <li>15 requests</li>
                                            <li>Gross yearly income</li>
                                            <li>Gross monthly income</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card col-md-3 mb-4 box-shadow" style="padding:0px;">
                                    <div class="card-header">
                                        <h4 class="my-0 font-weight-normal">Standard</h4>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                        <div class="w-100">
                                            <h1 class="card-title pricing-card-title">$19.99<small class="text-muted">/ mo</small></h1>
                                            <ul class="list-unstyled mt-3 mb-4 planPoints">
                                            <li>250 requests</li>
                                            <li>Calculate monthly and yearly cash flow for muti-unit properties</li>
                                            <li>Rent data provided for property</li>
                                            <li>Total cash flow data month and yearly after property expenses</li>
                                            <li>Monthly and Yearly net operator costs</li>
                                            <li>Principal and interest rate for property</li>
                                            <li>Capitalization Rate for property</li>
                                            </ul>
                                        </div>
                                        <form action="{{ route('billing-checkout') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $email ?? '' }}">
                                            <button class="btn btn-lg btn-block btn-primary" name="selectedPlan" value="price_1Lni1TEviaLTUto6O4tgAZcX">Get started</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card col-md-3 mb-4 box-shadow" style="padding:0px;">
                                    <div class="card-header">
                                        <h4 class="my-0 font-weight-normal">Premium</h4>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                        <div class="w-100">
                                            <h1 class="card-title pricing-card-title">$39.99 <small class="text-muted">/ mo</small></h1>
                                            <ul class="list-unstyled mt-3 mb-4 planPoints">
                                            <li>Unlimited requests</li>
                                            <li>Calculate monthly and yearly cash flow for muti-unit properties</li>
                                            <li>Rent data provided for property</li>
                                            <li>Total cash flow data month and yearly after property expenses</li>
                                            <li>Monthly and Yearly net operator costs</li>
                                            <li>Principal and interest rate for property</li>
                                            <li>Capitalization Rate for property</li>
                                            </ul>
                                        </div>
                                        <form action="{{ route('billing-checkout') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $email ?? '' }}">
                                            <button class="btn btn-lg btn-block btn-primary" name="selectedPlan" value="price_1Lni1vEviaLTUto6DsnimYJU">Get started</button>
                                        </form>
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
            
        });
    </script>
    
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/main.js') }}"></script>
@endsection