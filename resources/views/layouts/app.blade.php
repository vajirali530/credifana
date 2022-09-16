<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/favicon/apple-icon-57x57.png') }}" />
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/favicon/apple-icon-60x60.png') }}" />
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/favicon/apple-icon-72x72.png') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/favicon/apple-icon-76x76.png') }}" />
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/favicon/apple-icon-114x114.png') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/favicon/apple-icon-120x120.png') }}" />
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/favicon/apple-icon-144x144.png') }}" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/favicon/apple-icon-152x152.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-icon-180x180.png') }}" />
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('images/favicon/android-icon-192x192.png') }}" />
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon/favicon-96x96.png') }}" />
    <link rel="manifest" href="{{ asset('images/favicon/manifest.json') }}" />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{ asset('css/lib/bootstrap.min.css') }}" />
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <title>Credifana | Manage your credit cards</title>
</head>
<body>
    <div class="body-wrapper">
        <header>
            <nav class="navbar navbar-expand-sm navbar-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="38" height="38" class="d-inline-block align-text-top">
                        <h1>Credifana</h1>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item px-2">
                                <a class="nav-link" href="{{ route('pricing') }}">Pricing</a>
                            </li>
                            <li class="nav-item px-2">
                                <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                            </li>
                            <li class="nav-item px-2 btn-item">
                                <a class="nav-link btn" href="#">Download</a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </nav>
        </header>
        <main>
            @yield('content')
        </main>

        <footer>
            <div class="container">
                <div class="copyright">
                    <p>&copy; Copyright 2021 Credifana. All rights reserved</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    @yield('scripts')
</body>
</html>