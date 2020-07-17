@extends('layouts.landing')

@section('content')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
<!-- ***** Welcome Area Start ***** -->
<section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <!-- Welcome Intro Start -->
            <div class="col-12 col-md-8 col-lg-8">
                <div class="welcome-intro">
                    <h1 class="text-white">Create and manage your<br>
                        <span class="high"> FIFA tournament.</span>
                    </h1>
                    <p class="text-white">The fixtures and results table will be generated automatically. <br>
                        Players will never play against their own teams.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Shape Bottom -->
    <div class="shape-bottom">
    </div>
</section>
<!-- ***** Welcome Area End ***** -->
<section class="section service-area bg-gray overflow-hidden ptb_100">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-12 col-lg-7 col-md-5">
                <div class="service">
                    <img src="{{ asset('landing/img/about_phone.png') }}">
                </div>
            </div>
            <div class="col-12 col-lg-5 col-md-5">
                <div class="service_text text-center">
                    <img src="{{ asset('landing/img/about_logo.png') }}">
                    <p>The TOURNIE App Is Now Available On <br>
                        All Ios And Andriod Devices.
                    </p>
                    <div class="button-group store-buttons d-flex justify-content-center">
                        <a href="{{$config->google_play_url}}">
                            <img src="{{ asset('landing/img/google.png') }}" alt="">
                        </a>
                        <a href="{{$config->app_store_url}}">
                            <img src="{{ asset('landing/img/app_store.png') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <!-- Button trigger modal -->
            <button type="button" class="btn video-btn" data-src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-toggle="modal" data-target="">
                <div class="styles_animation__1Lamn"></div>
            </button>
            <button type="button" class="btn video-btn btn2" data-src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-toggle="modal" data-target="">
                <div class="styles_animation__1Lamn2"></div>
            </button>
            <button type="button" class="btn video-btn btn3" data-src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-toggle="modal" data-target="">
                <div class="styles_animation__1Lamn2"></div>
            </button>
            <button type="button" class="btn video-btn btn4" data-src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-toggle="modal" data-target="">
                <div class="styles_animation__1Lamn2"></div>
            </button>
            <div class="styles_animation__1Lamn"></div>
            <!-- Modal -->
        </div>
    </div>
</section>
<!-- ***** Features Area Start ***** -->
<section id="features" class="section features-area">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 col-lg-7">
                <!-- Section Heading -->
                <div class="section-heading">
                    <h2>Features List</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-10 col-lg-10">
                <!-- Image Box -->
                <div class="image-box">
                    <ul>
                        <li>  Create your Tournament</li>
                        <li>  Update Score of Matches</li>
                        <li> Add some players</li>
                        <li>View Fixtures & Tables </li>
                        <li>Assign teams to each player </li>
                        <li> Invite Friends and Opponent</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Features Area End ***** -->
<!--====== Contact Area Start ======-->

<section id="contact" class="contact-area ptb_50">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <!-- Contact Us -->
                <div class="contact-us">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <!-- Contact Box -->
                <div class="contact-box">
                    <!-- Section Heading -->
                    <div class="heading">
                        <h2 class="text-capitalize">Send Us message</h2>
                    </div>
                    <div class="alert alert-success" id="res_message" style="display:none;">
                        
                    </div>
                    <!-- Contact Form -->
                    <div style="padding:30px 40px;background: white;box-shadow: 1px 4px 5px 6px #4229a80f;">
                        <form id="contactform" method="POST" action="javascript:void(0)">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="Your Name" id="name" required="required">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="Your Email" id="email" required="required">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="phone" id="phone" minlength="5" type="tel" onkeyup="if (/\D/g.test(this.value))
        this.value = this.value.replace(/\D/g, '')"placeholder="Phone Number" required="required">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea class="form-control" name="message" placeholder="Message" id="message" required="required"></textarea>
                                    </div>
                                </div>
                                <!--<input type="submit" value='submit' name="submit"/>-->
                                <div class="col-12">
                                    <button type="submit" id="bringform" class="btn btn-lg btn-block mt-3"><span class="text-white"></span>Get Started <i class="fas fa-angle-double-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== Contact Area End ======-->
<script>
    $(document).ready(function () {
        $('#contactform').on('submit', function (e) {
            e.preventDefault();
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var form_data = new FormData();
            form_data.append("name", name);
            form_data.append("email", email);
            form_data.append("phone", phone);
            form_data.append("subject", subject);
            form_data.append("message", message);
            form_data.append("_token", $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                url: "{{route('contactformsubmit')}}",
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    //                        Swal.showLoading();
                },
                success: function (response)
                {
//                    $('#send_form').html('Submit');
                    $('#res_message').show();
                    $('#res_message').html(response.msg);
                    

                    document.getElementById("contactform").reset();
                    setTimeout(function () {
                        $('#res_message').hide();
                    }, 10000);
                }
            });
        });
    });

</script>

@endsection
