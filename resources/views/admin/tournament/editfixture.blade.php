@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Edit Fixture #{{ $tournamentfixture->id }}</div>
                <div class="card-body">
                    <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <br />
                    <br />

                    @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @endif

                    <form action="{{url('admin/updateTournamentFixture/'.$tournamentfixture->id)}}" method="post"> 
                        @csrf
                        <div class="form-group">
                            <label for="player_id_1_score" class="control-label"><?php
                                        $user = DB::table('users')->where('id', $tournamentfixture->player_id_1)->first();
                                        $team = DB::table('teams')->where('id', $tournamentfixture->player_id_1_team_id)->first();
                                        echo $user->username;
                                        echo ' ('.$team->team_name.')';
                                        ?> score: </label>
                            <input class="form-control" required="required" name="player_id_1_score" type="text" value="{{$tournamentfixture->player_id_1_score}}" id="player_id_1_score">

                        </div>
                        <div class="form-group">
                            <label for="player_id_2_score" class="control-label"><?php
                                        $user = DB::table('users')->where('id', $tournamentfixture->player_id_2)->first();
                                        $team = DB::table('teams')->where('id', $tournamentfixture->player_id_2_team_id)->first();
                                        echo $user->username;
                                        echo ' ('.$team->team_name.')';
                                        ?> score: </label>
                            <input class="form-control" required="required" name="player_id_2_score" type="text" value="{{$tournamentfixture->player_id_2_score}}" id="player_id_2_score">

                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Update">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
