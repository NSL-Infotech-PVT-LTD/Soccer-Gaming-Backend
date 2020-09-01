@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tournament Details</div>
                <div class="card-body">

                    <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
<!--                    <a href="{{ url('/admin/tournament/' . $tournament->id . '/edit') }}" title="Edit Tournament"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                    {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['admin/tournament', $tournament->id],
                    'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-sm',
                    'title' => 'Delete Tournament',
                    'onclick'=>'return confirm("Confirm delete?")'
                    ))!!}
                    {!! Form::close() !!}
                    <br/>-->
                    <br/>

                    <div class="table-responsive">
                        <table class="table">
                            <col width="350"> 
                            <tbody>
                                <tr>
                                    <th>ID</th><td>{{ $tournament->id }}</td>
                                </tr>
                                <tr><th> Name </th><td> {{ $tournament->name }} </td></tr><tr><th> Type </th><td> {{ $tournament->type }} </td></tr><tr><th> Number Of Teams per Players </th><td> {{ $tournament->number_of_teams_per_player }} </td></tr><tr><th> Number Of Players </th><td> {{ $tournament->number_of_teams_per_player }} </td></tr><tr><th> Number Of Plays against each Team </th><td> {{ $tournament->number_of_plays_against_each_team }} </td></tr>
                                <?php if ($tournament->type != 'league') { ?>
                                    <tr width = '10'><th> Number of Players in knockout stage</th><td> {{ $tournament->number_of_players_that_will_be_in_the_knockout_stage }} </td></tr><tr><th> Legs per Match in Knockout stage</th><td> {{ $tournament->legs_per_match_in_knockout_stage }} </td></tr><tr><th> Number of legs in Final </th><td> {{ $tournament->number_of_legs_in_final }} </td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <?php
            $playersTeams = DB::table('tournament_player_teams')->where('tournament_id', $tournament->id)->get();
            ?>        
            <div class="card">
                <div class="card-header">Tournament Players & Teams</div>
                <div class="card-body">

                    <br/>
                    <div class="">
                        <table class="mytable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Player Name</th><th>Team</th><th>Image</th>
                                </tr>
                            </thead>
                            <tbody><?php $i = 1; ?>
                                @foreach($playersTeams as $item)
                                <tr>
                                    <td>{{$i++ }}</td>
                                    <td>
                                        <?php
                                        $user = DB::table('users')->where('id', $item->player_id)->first();
                                        echo $user->first_name;
                                        ?> 
                                    </td>
                                    <td>
                                        <?php
                                        if (is_numeric($item->team_id)):
                                            $teams = DB::table('teams')->where('id', $item->team_id)->first();
                                            echo $teams->team_name;
                                        else:
                                            echo $item->team_id;
                                        endif;
                                        ?> 
                                    </td>
                                    <td>
                                        <?php
                                        if (is_numeric($item->team_id)):
                                            $teams = DB::table('teams')->where('id', $item->team_id)->first();
                                            echo "<img width='50' src=" . $teams->image . ">";
                                        else:
                                            echo 'NA';
                                        endif;
                                        ?>    
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th><th>Player Name</th><th>Team</th><th>Image</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.mytable').DataTable({
            'columns': [
                {data: 'first_name'}, // index - 0
                {data: 'team_name'}, // index - 1
                {data: 'image'}  // index - 2
            ],
            'columnDefs': [{
                    'targets': [0, 1, 2], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
        });
    });
</script>
@endsection
