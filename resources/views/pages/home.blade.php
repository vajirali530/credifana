@extends('layouts.app')

@section('content')
    
    <section class="hero-container">
        <div class="container-lg">
            <div class="hero-wrapper">
                <div class="row align-items-end">
                    <div class="col-md-6 order-md-1 order-2">
                        <div class="left-block">
                            <div class="hero-content">
                                <h2>
                                    <span class="line-break"> Shop your <span class="green bold special-text">favorite</span> stores </span>
                                    <span class="line-break"> While know your <span class="orange bold special-text">interest</span> </span>
                                    <span class="line-break"> <span class="primary bold special-text">Rates</span> before you buy </span>
                                </h2>
                                <div class="hero-btn">
                                    <a href="#" class="btn btn-outline-primary download-btn std-btn">Download now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 order-md-2 order-1">
                        <div class="right-block">
                            <div class="hero-image">
                                <img src="{{ asset('images/main-hero.png') }}" alt="Hero image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="intro-section">
        <div class="container-lg">
            <div class="intro-wrapper">
                <div class="intro-que">
                    <div class="heading">
                        <h2>What can Credifana do?</h2>
                    </div>
                </div>
                <div class="intro-ans content">
                    <p>
                        Transform the way you look at your credit cards, stay ahead of these credit card companies, know your balance, interest rates, number of months it would take to lower balance all while you shop
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="process-section">
        <div class="container-lg">
            <div class="process-wrapper">
                <div class="heading">
                    <h2>How Credifana works</h2>
                </div>
                <div class="process-steps">
                    <div class="step">
                        <div class="step-wrapper">
                            <div class="row">
                                <div class="col-md-6 order-1 order-md-1">
                                    <div class="left-block">
                                        <div class="heading">
                                            <h3>Select your credit card</h3>
                                        </div>
                                        <div class="content">
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa, id, obcaecati sequi laudantium praesentium facere pariatur expedita impedit repudiandae dicta ullam accusamus voluptate ut asperiores consequatur quam error sit laboriosam?
                                                Temporibus hic voluptatem quas officia unde odit porro corporis possimus perspiciatis dolor quae esse ut rem commodi doloremque, ratione totam deleniti quaerat, fugit, nesciunt necessitatibus ex magnam? Ipsum, possimus veniam.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 order-2 order-md-2">
                                    <div class="right-block">
                                        <div class="image">
                                            <img src="{{ asset('images/dummy.png') }}" alt="Dummy image" width="440px" height="440px" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-wrapper">
                            <div class="row">
                                <div class="col-md-6 order-2 order-md-1">
                                    <div class="left-block">
                                        <div class="image">
                                            <img src="{{ asset('images/dummy.png') }}" alt="Dummy image" width="440px" height="440px" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 order-1 order-md-2">
                                    <div class="right-block">
                                        <div class="heading">
                                            <h3>Select item from your favorite store</h3>
                                        </div>
                                        <div class="content">
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa, id, obcaecati sequi laudantium praesentium facere pariatur expedita impedit repudiandae dicta ullam accusamus voluptate ut asperiores consequatur quam error sit laboriosam?
                                                Temporibus hic voluptatem quas officia unde odit porro corporis possimus perspiciatis dolor quae esse ut rem commodi doloremque, ratione totam deleniti quaerat, fugit, nesciunt necessitatibus ex magnam? Ipsum, possimus veniam.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-wrapper">
                            <div class="row">
                                <div class="col-md-6 order-1 order-md-1">
                                    <div class="left-block">
                                        <div class="heading">
                                            <h3>Add item to cart</h3>
                                        </div>
                                        <div class="content">
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa, id, obcaecati sequi laudantium praesentium facere pariatur expedita impedit repudiandae dicta ullam accusamus voluptate ut asperiores consequatur quam error sit laboriosam?
                                                Temporibus hic voluptatem quas officia unde odit porro corporis possimus perspiciatis dolor quae esse ut rem commodi doloremque, ratione totam deleniti quaerat, fugit, nesciunt necessitatibus ex magnam? Ipsum, possimus veniam.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 order-1 order-md-1">
                                    <div class="right-block">
                                        <div class="image">
                                            <img src="{{ asset('images/dummy.png') }}" alt="Dummy image" width="440px" height="440px" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stores-section">
        <div class="container-lg">
            <div class="stores-list-wrapper">
                <div class="heading">
                    <h3>Current store integrated with credifana</h3>
                </div>
                <div class="stores-list">
                    <div class="images">
                        <img src="{{ asset('images/stores/amazon.png') }}" alt="Amazon" width="200" height="100" />
                        <img src="{{ asset('images/stores/ebay.png') }}" alt="Ebay" width="200" height="100" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="add-store-section">
        <div class="container">
            <div class="store-add-contact">
                <div class="heading">
                    <h2>Want us to add your favorite store? Tell us</h2>
                </div>
                <div class="contact-btn">
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary std-btn">Contact us</a>
                </div>
            </div>
        </div>
    </section>

    <section class="cc-repair-section">
        <div class="container">
            <div class="repair-container">
                <div class="heading">
                    <h2>Repair your Credit Card</h2>
                </div>
                <div class="content">
                    <p>
                        Credit is very important us and if you are interested in repairing your credit let us know we would be happy to help you
                    </p>
                </div>
                <div class="contact-btn">
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary std-btn-full">Contact us</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script src="{{ asset('js/lib/gsap.min.js') }}"></script>
    <script src="{{ asset('js/lib/gsap.scrollTrigger.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/animation.js') }}"></script>
@endsection