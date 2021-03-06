

<!DOCTYPE HTML>
<html>
     <head>
        <title>{{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="{{ config('app.name') }}" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="{{ asset('template/css/style.css') }} " rel='stylesheet' type='text/css' />
        <!-- Graph CSS -->
        <link href="{{ asset('template/css/font-awesome.css') }}" rel="stylesheet">
        <!-- jQuery -->
        <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
        <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <!-- lined-icons -->
        {{--        <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />--}}
        {{--        <script src="{{ asset('template/js/simpleCart.min.js') }} "> </script>--}}
    {{--        <script src="{{ asset('template/js/amcharts.js') }} "></script>--}}
{{--        <script src="{{ asset('template/js/serial.js') }}  "></script>--}}
{{--        <script src="{{ asset('template/js/light.js') }} "></script>--}}
<!-- //lined-icons -->

<script src="{{asset('js/jquery-1.11.1.min.js')}}"></script>


<!--datatable starts-->

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<!--datatable ends-->
{{--        <script src="{{ asset('template/js/jquery-1.10.2.min.js') }} "></script>--}}
<!--pie-chart--->
{{--        <script src="{{ asset('template/js/pie-chart.js') }} " type="text/javascript"></script>--}}


<!--charts js starts-->

<script src ="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js">
<link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</script>

<!--charts js ends-->



</head>
<body>
    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">
                <!-- header-starts -->
                <div class="header-section">
                    <!-- top_bg -->
                    <div class="top_bg">

                        <div class="header_top">
                            <div class="top_right">
                                <img style = "width: 138px;" src = "{{ asset('logo.png') }}">
                            </div>
                            <div class="top_left">


                                <a class="#" style ="Color:White" href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                            <div class="clearfix"> </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <!-- /top_bg -->
                </div>

                <!-- //header-ends -->

                <!--content-->
                <div class="content">
                    <main class="py-4">
                        @if (Session::has('flash_message'))
                        <div class="container">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ Session::get('flash_message') }}
                            </div>
                        </div>
                        @endif

                        @yield('content')
                    </main>

                </div>
                <!--content-->
            </div>
        </div>
        <!--//content-inner-->
        <!--/sidebar-menu-->
        <div class="sidebar-menu">
            <header class="logo1">
                <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>
            </header>
            <div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
            <div class="menu">
                <ul id="menu" >
                    <li><a href="{{ url('admin/home')}}"><i class="fa fa-tachometer"></i> <span>Home</span></a></li>
                    <li>
                        <a href="{{ url('admin/users/role/1') }}"  data-toggle="collapse" data-target="#demo"><i class="fa fa-user nav_icon"></i>Users<span class=""></span></a>     

                        <ul>
<?php foreach (\App\Role::all() as $role): ?>
                                <li  id='demo'>
                                    <a href="{{ url('admin/users/role/'.$role->id) }}">{{$role->name}}<span class=""></span></a>
                                </li>  
<?php endforeach; ?>
                        </ul>
                    </li>
                    
                   


                      
                    <li><a href="{{ url('admin/generator')}}"><i class="fa fa-bars"></i> <span>Generator</span></a></li>

                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <script>
        var toggle = true;

        $(".sidebar-icon").click(function () {
            if (toggle)
            {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({"position": "absolute"});
            } else
            {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function () {
                    $("#menu span").css({"position": "relative"});
                }, 400);
            }

            toggle = !toggle;
        });
        $("#menu li a").each(function () {
            if ((window.location.href.indexOf($(this).attr('href'))) > -1) {
                $(this).parent().addClass('active');
            }
        });
    </script>
    <!--js 
    {{--        <script src="{{ asset('template/js/jquery.nicescroll.js') }} "></script>--}}
{{--        <script src="{{ asset('template/js/scripts.js') }} "></script>--}}
{{--        <!-- Bootstrap Core JavaScript -->--}}
<script src="{{ asset('template/js/bootstrap.min.js') }} "></script>
{{--        <!-- /Bootstrap Core JavaScript -->--}}
{{--        <!-- real-time -->--}}
{{--        <script language="javascript" type="text/javascript" src="js/jquery.flot.js"></script>--}}
{{--        <script src="{{ asset('template/js/jquery.fn.gantt.js') }} "></script>--}}
<script src="{{ asset('template/js/menu_jquery.js') }}  "></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

 <!-- DataTable -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
    <!-- End DataTable -->

</body>
</html>



