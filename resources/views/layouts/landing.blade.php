<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Title  -->
        <title><?= (request()->segment(count(request()->segments())) == '') ? config('app.name') : config('app.name') . ' | ' . ucwords(request()->segment(count(request()->segments()))) ?></title>
        <meta name="description" content="{{ strip_tags(\DB::table('configurations')->get()->first()->about_us_customer)}}"/>
        <meta name="og:title" property="og:title" content="{{ strip_tags(\DB::table('configurations')->get()->first()->about_us_customer)}}">
        <!-- Favicon  -->
        <link rel="icon" type="image/png" href="{{ asset('landing/img/fav.png') }}" />
        <!-- ***** All CSS Files ***** -->
        <!-- Style css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/style.css') }}">
        <!-- Responsive css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/responsive.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            .footer_bottom img {
                max-width: 361px;
                margin: auto;
            }

            .footer_bottom {
                width: 100%;
                text-align: center;
                padding-top: 50px;
            }
            .term_text h1, .term_text h2 {
                background-color: transparent !important;
            }
            body {
                overflow: hidden;
            }
            /* Preloader */

            #preloader {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #fff;
                /* change if the mask should have another color then white */
                z-index: 9999;
                /* makes sure it stays on top */
            }

            #status {
                width: 200px;
                height: 200px;
                position: absolute;
                left: 50%;
                /* centers the loading animation horizontally one the screen */
                top: 50%;
                /* centers the loading animation vertically one the screen */
                /*background-image: url(landing/img/loader.gif);*/
                /* path to your loading animation */
                background-repeat: no-repeat;
                background-position: center;
                margin: -100px 0 0 -100px;
                /* is width and height divided by two */
            }
        </style>
    </head>
    <body>
        <!--====== Preloader Area Start ======-->
        <!--        <div class="preloader-main">
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
                </div>-->
        <div id="preloader">
            <div id="status">&nbsp;<img width="150" src="{{url('landing/img/loader.gif')}}"></div>
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
                                    <a class="nav-link {{ Route::currentRouteNamed( 'front.home' ) ?  'active' : '' }}" href="{{route('front.home')}}">Home</a>
                                </li>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteNamed( 'aboutus' ) ?  'active' : '' }}" href="{{route('aboutus')}}">About us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteNamed( 'terms_conditions' ) ?  'active' : '' }}" href="{{route('terms_conditions')}}">Terms & Conditions</a>
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
                <div class="footer-top">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <!-- Footer Items -->
                                <div class="footer-items">
<!--                                                <p class="mt-2 mb-3">Finaldream rure dolor in reprehenderit in voluptate velit esse cillum dolore e uis nostrud exercitation isi ut aliquip ex ea commodo consequat.</p>-->
                                    <!-- Social Icons -->
                                    <div class="social-icons d-flex">
                                        <a class="nav-link" href="{{$config->facebook_url}}" target="_blank"><i class="fab fa-facebook-f"></i>
                                        </a>
                                        </li> 
                                        <a class="nav-link" href="{{$config->youtube_url}}" target="_blank"><i class="fab fa-youtube"></i>
                                        </a>
                                        <a class="nav-link" href="{{$config->instagram_url}}" target="_blank"><i class="fab fa-instagram"></i>
                                        </a>
                                        <a class="nav-link" href="{{$config->twitch}}" target="_blank"> <i class="fab fa-twitch"></i>
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
                                        <li class="py-2"><a href="{{$config->google_play_url}}" target="_blank">Google Play Store
                                            </a>
                                        </li>
                                        <li class="py-2"><a href="{{$config->app_store_url}}" target="_blank">Apple Store
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
                                        <li class="py-2"><a href="{{route('terms_conditions')}}">Terms & Conditions
                                            </a>
                                        </li>
                                        <li class="py-2"><a class="scroll" href="{{url('/')}}#features">Features</a></li>
                                        <li class="py-2"><a href="{{route('privacypolicy')}}">Privacy Policy</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <!-- Footer Items -->
                                <div class="footer-cnt">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Contact Us</h3>
                                    <ul>
                                        <li> <i class="fas fa-map-marker-alt"></i>{{$config->address}}</li>
                                        <li><i class="fas fa-phone-alt"></i><a href="tel:">{{$config->phone_number}}</a></li>
                                        <li><i class="fas fa-envelope"></i><a href="mailto:">{{$config->email}}</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="footer_bottom">
                                <a href="https://www.netscapelabs.com/" target="_blank"><img src="{{url('landing/img/footer_image.png')}}"></a>
</div>


</div>
</div>
</div>
</footer>
<!--                ====== Footer Area End ======-->
              </div>
                    <!--========= ***** All jQuery Plugins ***** ====        =======================-->
<script src=" {{    asset('landing/js/jquery/jquery-3.3.1.min.js') }}"></script>
        <script src=" {{ asset('landing/js/bootstrap/popper.min.js') }}"></script>
        <!--===============================================================================================-->
        <script src=" {{ asset('landing/js/bootstrap/bootstrap.min.js') }}"></script>
        <script src=" {{ asset('landing/js/bootstrap/bootstrap.min.js') }}"></script>
        <script src=" {{ asset('landing/js/plugins/plugins.min.js') }}"></script>
        <script src=" {{ asset('landing/js/active.js') }}"></script>
        <script>
$(window).on('load', function () { // makes sure the whole site is loaded 

    $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
    $('#status').delay(350).fadeOut('slow'); // will first fade out the loading animation 
    $('body').delay(350).css({'overflow': 'visible'});
});
        </script>
    </body>
</html>