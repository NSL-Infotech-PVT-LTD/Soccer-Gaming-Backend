@extends('layouts.landing')

@section('content')
<div class="inner_page">
    <section id="home" class="section welcome-area  term_condition bg-overlay overflow-hidden d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <!-- Welcome Intro Start -->
                <div class="col-12 col-md-7 col-lg-7">
                    <div class="welcome-intro">
                        <h1 class="text-white">Terms & <br>Condition</h1>
                        <p class="text-white">The fixtures and results table will be generated automatically. 
                            Players will never play against their own teams.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-lg-5">
                    <img src="{{ asset('landing/img/term_phone.png') }}">
                    
                    <button type="button" class="btn video-btn btnterm" data-src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-toggle="modal" data-target="">
                <div class="styles_animation__1Lamn2"></div>
            </button>
                    
                    
                       <button type="button" class="btn video-btn btnterm2" data-src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-toggle="modal" data-target="">
                <div class="styles_animation__1Lamn2"></div>
            </button>
                    
                 <button type="button" class="btn video-btn btnterm3" data-src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-toggle="modal" data-target="">
                <div class="styles_animation__1Lamn2"></div>
            </button>
                    
                </div>
            </div>
        </div>
        <!-- Shape Bottom -->
        <div class="shape-bottom">
        </div>
    </section>
    <section id="term_condition" class="condition pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="term_text">
                        <ul>
                            <li>{!! $config->terms_and_conditions_customer !!}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
