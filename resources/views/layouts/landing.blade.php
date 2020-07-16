<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Title  -->
        <title>Tournie</title>
        <!-- Favicon  -->
        <link rel="icon" type="image/png" href="{{ asset('landing/img/fav.png') }}" />
        <!-- ***** All CSS Files ***** -->
        <!-- Style css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/style.css') }}">
        <!-- Responsive css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/responsive.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    </head>
    <body>
        <!--====== Preloader Area Start ======-->
        <div class="preloader-main">
            <div class="preloader-wapper">
                <svg class="preloader" xmlns="http://www.w3.org/2000/svg" version="1.1" width="600" height="200">
                <defs>
                <filter id="goo" x="-40%" y="-40%" height="200%" width="400%">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="" />
                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -8" result="goo" />
                </filter>
                </defs>
                <g filter="url(#goo)">
                <circle class="dot" cx="50" cy="50" r="25" fill="#92000b" />
                <circle class="dot" cx="50" cy="50" r="25" fill="#164659" />
                </g>
                </svg>
                <div>
                    <div class="loader-section section-left"></div>
                    <div class="loader-section section-right"></div>
                </div>
            </div>
        </div>
        <!--====== Scroll To Top Area Start ======-->
        <div id="scrollUp" title="Scroll To Top">
            <i class="fas fa-arrow-up"></i>
        </div>
        <!--====== Scroll To Top Area End ======-->
        <div class="main">
            <!-- ***** Header Start ***** -->
            <header class="navbar navbar-expand-lg navbar-dark">
                <div class="container position-relative">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img class="navbar-brand-regular" src="{{ asset('landing/img/logo/logo.png') }}" alt="brand-logo">

                    </a>
                    <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-inner">
                        <!--  Mobile Menu Toggler -->
                        <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <nav>
                            <ul class="navbar-nav" id="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{url('/')}}">Home</a>
                                </li>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/')}}#features">Features</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('terms_conditions')}}">Terms & Conditions</a>
                                </li>
                                <li class="nav-item socail">
                                    <a class="nav-link" href="{{$config->facebook_url}}" target="_blank"><i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li class="nav-item socail">
                                    <a class="nav-link" href="{{$config->youtube_url}}" target="_blank"><i class="fab fa-youtube"></i>
                                    </a>
                                </li>
                                <li class="nav-item socail">
                                    <a class="nav-link" href="{{$config->instagram_url}}" target="_blank"><i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                                <li class="nav-item socail">
                                    <a class="nav-link" href="{{$config->twitch}}" target="_blank"> <i class="fab fa-twitch"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>

            @yield('content')

            <!--====== Footer Area Start ======-->
            <footer class="footer-area">
                <!-- Footer Top -->
                <div class="footer-top ptb_100">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <p class="mt-2 mb-3">Finaldream rure dolor in reprehenderit in voluptate velit esse cillum dolore e uis nostrud exercitation isi ut aliquip ex ea commodo consequat.</p>
                                    <!-- Social Icons -->
                                    <div class="social-icons d-flex">
                                        <a class="nav-link" href="{{$config->facebook_url}}"><i class="fab fa-facebook-f"></i>
                                        </a>
                                        </li> 
                                        <a class="nav-link" href="{{$config->youtube_url}}"><i class="fab fa-youtube"></i>
                                        </a>
                                        <a class="nav-link" href="{{$config->instagram_url}}"><i class="fab fa-instagram"></i>
                                        </a>
                                        <a class="nav-link" href="{{$config->twitch}}"> <i class="fab fa-twitch"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Download App</h3>
                                    <ul>
                                        <li class="py-2"><a href="{{$config->google_play_url}}">Google Play Store
                                            </a>
                                        </li>
                                        <li class="py-2"><a href="{{$config->app_store_url}}">Apple Store
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Our Links</h3>
                                    <ul>
                                        <li class="py-2"><a href="{{url('terms_conditions')}}">Terms & Conditions
                                            </a>
                                        </li>
                                        <li class="py-2"><a href="{{url('aboutus')}}">About us</a></li>
                                        <li class="py-2"><a href="{{url('privacypolicy')}}">Privacy Policy</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <!-- Footer Items -->
                                <div class="footer-cnt">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Contact Us</h3>
                                    <ul>
                                        <li> <i class="fas fa-map-marker-alt"></i>Al. Dummyodl 124/23 floor 123 Lipsum Street, 02-577 USA.</li>
                                        <li><i class="fas fa-phone-volume"></i> <a href="tel:">00 387 65 302 657</a></li>
                                        <li><i class="far fa-envelope"></i><a href="mailto:">hello@inkyy.com</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!--====== Footer Area End ======-->
        </div>
        <!--========= ***** All jQuery Plugins ***** ===========================-->
        <script src=" {{ asset('landing/js/jquery/jquery-3.3.1.min.js') }}"></script>
        <script src=" {{ asset('landing/js/bootstrap/popper.min.js') }}"></script>
        <!--===============================================================================================-->
        <script src=" {{ asset('landing/js/bootstrap/bootstrap.min.js') }}"></script>
        <script src=" {{ asset('landing/js/bootstrap/bootstrap.min.js') }}"></script>
        <script src=" {{ asset('landing/js/plugins/plugins.min.js') }}"></script>
        <script src=" {{ asset('landing/js/active.js') }}"></script>
        <script>
            $(document).ready(function () {
                $('.nav-item a').click(function () {
//                    alert('m here');
                    $('.nav-item a').removeClass("active");
                    $(this).addClass("active");
                });
            });
        </script>
    </body>
</html>