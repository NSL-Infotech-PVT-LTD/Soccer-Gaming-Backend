@extends('layouts.backend')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
<div class="container">

    <div class="card">
        <div class="card-header"><h3 class="card-text animated  rubberBand delay-1s"><b>Dashboard</b></h3></div>
   
    </div>  
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <a href="{{url('admin/users')}}" class="links" style="text-decoration: none;">
                    <div class="main-overview active">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Total App-Users&nbsp;&nbsp;<i class="fa fa-users"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($users = App\User::get());
                                ?></div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-md-4">
                <a href="{{url('admin/ads')}}" class="links" style="text-decoration: none;">
                    <div class="main-overview">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Total Ads&nbsp;&nbsp;<i class="fa fa-picture-o"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($users = App\Ad::get());
                                ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{url('admin/features')}}" class="links" style="text-decoration: none;">
                    <div class="main-overview">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Total Free Features&nbsp;&nbsp;<i class="fa fa-unlock"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($users = App\Feature::get());
                                ?></div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4">
                <a href="{{url('admin/users/subscribed')}}" class="links" style="text-decoration: none;">
                    <div class="main-overview">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Subscribed Users&nbsp;&nbsp;<i class="fa fa-users"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($users = App\User::where('id', '!=', Auth::id())->where('payment_status', '=', '2')->get());
                                ?></div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{url('admin/users/unsubscribed')}}" class="links" style="text-decoration: none;">
                    <div class="main-overview">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">UnSubscribed Users&nbsp;&nbsp;<i class="fa fa-users"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($users = App\User::where('id', '!=', Auth::id())->where('payment_status', '=', '1')->get());
                                ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{url('admin/users/freetrial')}}" class="links" style="text-decoration: none;">
                    <div class="main-overview">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">On Free Trial&nbsp;&nbsp;<i class="fa fa-users"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($users = App\User::where('id', '!=', Auth::id())->where('payment_status', '=', '0')->get());
                                ?></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
