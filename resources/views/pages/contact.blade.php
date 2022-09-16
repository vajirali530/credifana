@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
@endsection

@section('content')

    <section class="contact-hero-section">
        <div class="container-lg">
            <div class="hero-wrapper">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="left-block">
                            <div class="image">
                                <img src="{{ asset('images/contact-hero.png') }}" alt="Contact Credifana image" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-block">
                            <div class="top-content">
                                <div class="heading">
                                    <h1>Connect at Credifana</h1>
                                </div>
                                <div class="content">
                                    <p>
                                        We appreciate your interest in credifana. Have questions, Get in touch now!
                                    </p>
                                </div>
                            </div>
                            <div class="contact-form">
                                <div class="form-heading">
                                    <h2>Let's chat</h2>
                                </div>
                                <form action="" id="contact-form" method="POST">
                                    @csrf
                                    <div class="form-group mode-form-group">
                                        <input type="text" class="form-control" name="user_name" id="user_name"
                                            placeholder="Name" />
                                        <label for="user_name">Full name</label>
                                        <div class="error-message"></div>
                                    </div>
                                    <div class="form-group mode-form-group">
                                        <input type="email" class="form-control" name="user_email" id="user_email"
                                            placeholder="Email" />
                                        <label for="user_email">Email Address</label>
                                        <div class="error-message"></div>
                                    </div>
                                    <div class="form-group d-flex">
                                        <div class="country-code">
                                            <div class="form-group me-3 mb-0">
                                                <input type="text" class="form-control tel" name="country-code" tabindex="999" placeholder="" />
                                            </div>
                                            <div class="error-message"></div>
                                        </div>
                                        <div class="w-100">
                                            <div class="form-group mode-form-group mb-0">
                                                <input type="tel" class="form-control w-100" name="user_phone" id="user_phone" placeholder="Phone" />
                                                <label for="user_phone">Phone Number</label>
                                                <div class="error-message"></div>
                                                <input type="hidden" id="dial-code" class="form-control tel" name="dial-code" />
                                                <input type="hidden" id="country-name" class="form-control tel"
                                                    name="country-name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mode-form-group mb-4">
                                        <textarea type="text" class="form-control h-auto" name="user_requirement"
                                            id="user_requirement" cols="30" rows="5" placeholder="Your requirements"></textarea>
                                        <label for="user_requirement">How can we help you?</label>
                                        <div class="error-message"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                                    </div>
                                    <div class="send-btn d-flex align-items-center">
                                        <button class="btn std-btn-full" type="submit" name="submit_contact_form" value="submit_contact_form">
                                            <span class="text">Send</span>
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="1166 768 24 24"><path d="m1188.314 768.151-21.728 12.532a1.125 1.125 0 0 0 .103 2.025l4.983 2.09 13.469-11.866c.257-.23.623.122.403.389l-11.294 13.755v3.772c0 1.106 1.336 1.542 1.993.74l2.977-3.622 5.84 2.447a1.128 1.128 0 0 0 1.548-.853l3.375-20.246c.16-.947-.858-1.631-1.669-1.163Z" fill="#fff" fill-rule="evenodd" data-name="Icon awesome-paper-plane"/></svg>
                                            </span>
                                        </button>
                                        <div class="submit-loader ms-3 d-none">
                                            <div class="spinner-border text-primary align-middle"></div>
                                        </div>
                                    </div>

                                    <div class="captcha">
                                        <p>
                                            <span class="line-break"> Protected by Google recaptcha </span>
                                            <span class="line-break"> We guarantee 100% security of your information. </span>
                                            <span class="line-break"> We will not share the details you provide above with anyone. </span>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        const GOOGLE_CAPTCHA_SITE_KEY = "{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}";
        $(document).ready(function() {
            grecaptcha.ready(function() {
                grecaptcha.execute(GOOGLE_CAPTCHA_SITE_KEY, {
                    action: 'homepage'
                }).then(function(token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        });
    </script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_CAPTCHA_SITE_KEY') }}"></script>
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/lib/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/lib/intl.utils.min.js') }}"></script>
    <script src="{{ asset('js/lib/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
@endsection