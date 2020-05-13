@extends('layouts.backend')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
<style>
    .main-overview {
        height: 169px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(265px, 1fr)); /* Where the magic happens */
        grid-auto-rows: 94px;
        grid-gap: 20px;
        margin: 20px;
    }

    .overviewcard {
        height: 169px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        background-color: #382e72;
        text-decoration: none;
        border-radius:0px;
    }
    
    .overviewcard__icon {

        font-size: 18px;
        text-decoration: none;
    }
    .overviewcard__info {
        font-size: 27px;
        text-decoration: none;
    }
    .overviewcard:hover {
        background-color: #2b303a;
    }
</style>
<div class="container">

      
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <a href="{{ url('admin/users/role/2') }}" class="links" style="text-decoration: none;">
                    <div class="main-overview active">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Total App-Users&nbsp;&nbsp;<i class="fa fa-users"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($users = App\User::get());
                                ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-md-4">
                <a href="javascript:void(0)" class="links" style="text-decoration: none;">
                    <div class="main-overview">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Total Tournament&nbsp;<i class="fa fa-trophy"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($tournament = App\Tournament::get());
                               ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ url('admin/teams') }}" class="links" style="text-decoration: none;">
                    <div class="main-overview">
                        <div class="overviewcard">
                            <div class="overviewcard__icon">Total Teams&nbsp;&nbsp;<i class="fa fa-gamepad"></i></div>
                            <div class="overviewcard__info"><?php
                                echo count($teams = \App\Team::get());
                                ?></div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        
    </div>
</div>
@endsection
