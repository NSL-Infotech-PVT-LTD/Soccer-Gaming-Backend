@extends('layouts.backend')
@section('content')
<style>
    .accordion {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
        margin-top:15px;
    }

    .active, .accordion:hover {
        background-color: #ccc;
    }

    .accordion:after {
        content: '\002B';
        color: #777;
        font-weight: bold;
        float: right;
        margin-left: 5px;
    }

    .active:after {
        content: "\2212";
    }

    .panel {
        padding: 0 18px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
    }
    button:focus {
        outline: 1px dotted;
        outline: 0;
    }
</style>
<div class="container">
    <div class="row">
        @include('admin.sidebar')
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Fixture Report Detail {{ $tournamentFixtureByReportId->id }}</div>
                <div class="card-body">

                    <a href="{{ url('admin/player/fixtures/reported') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <h3 style="float:right;">Report Status : &nbsp;&nbsp;<?php $status = ucfirst($tournamentFixtureByReportId->status);
                                    if($status == null): echo "<span style='color:orange;font-weight: 500;'>Pending</span>"; elseif($status == 'Accept'): echo "<span style='color:green;font-weight: 500;'>$status</span>"; else: echo "<span style='color:red;font-weight: 500;'>$status</span>"; endif; ?></h3>
                    <br/>

                    <div class="table-responsive">
                        <button class="accordion">View All Details</button>
                        <div id="demo" class="panel">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ $tournamentFixtureByReportId->id }}</td>
                                    </tr>
                                    <tr>
                                        <th> Tournament Name </th>
                                        <td> 
                                            <?php
                                            $tournament = DB::table('tournaments')->where('id', $tournamentFixtureByReportId->tournament_id)->get();
                                            if ($tournament->isEmpty() != true)
                                                echo $tournament->first()->name;
                                            else
                                                echo $tournamentFixtureByReportId->tournament_id;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Tournament Type </th>
                                        <td><?= ($tournament->first()->type != null) ? ucwords(str_replace("_", " ", $tournament->first()->type)) : "-" ?></td>
                                    </tr>
                                    <tr>
                                        <th> Author </th>
                                        <td><?php
                                            $user = DB::table('users')->where('id', $tournamentFixtureByReportId->tournament_created_by_id)->get();
                                            if ($user->isEmpty() != true)
                                                echo $user->first()->username;
                                            else
                                                echo $tournamentFixtureByReportId->tournament_created_by_id;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Reported By </th>
                                        <td> 
                                            <?php
                                            $reportedBy = DB::table('users')->where('id', $tournamentFixtureByReportId->created_by)->get();
                                            if ($reportedBy->isEmpty() != true)
                                                echo $reportedBy->first()->username;
                                            else
                                                echo $tournamentFixtureByReportId->created_by;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Report Status </th>
                                        <td><?php $status = ucfirst($tournamentFixtureByReportId->status);
                                    if($status == null): echo "<span style='color:orange;font-weight: 500;'>Pending</span>"; elseif($status == 'Accept'): echo "<span style='color:green;font-weight: 500;'>$status</span>"; else: echo "<span style='color:red;font-weight: 500;'>$status</span>"; endif; ?></td>
                                    </tr>
                                    <tr>
                                        <th> Fixture Id </th>
                                        <td> {{ $tournamentFixtureByReportId->fixture_id }} </td></tr>
                                </tbody>
                            </table>   
                        </div> 
                        <h2>Score Details :</h2>
                        <table class="table">
                            <colgroup><col width="280"> 
                            </colgroup>
                            <tbody>
                                <tr> 
                                    <td style="text-align: right;"><b>Fixture Old Score</b></td>
                                    <td></td> 
                                </tr> 
                                <tr> 
                                    <td>Player 1 Score</td>
                                    <td>
                                        <?php
                                        $oldScoreFixture = \App\TournamentFixture::where('id', $tournamentFixtureByReportId->fixture_id)->get();
                                        if ($oldScoreFixture->isEmpty() != true)
                                            if ($oldScoreFixture->first()->player_id_1_score != null)
                                                echo $oldScoreFixture->first()->player_id_1_score;
                                            else
                                                echo '<b>No Score</b>';
                                        else
                                            echo '<b>No Score</b>';
                                        ?>
                                    </td>
                                </tr> 

                                <tr> 
                                    <td>Player 2 Score</td>
                                    <td>
                                        <?php
                                        if ($oldScoreFixture->isEmpty() != true)
                                            if ($oldScoreFixture->first()->player_id_1_score != null)
                                                echo $oldScoreFixture->first()->player_id_1_score;
                                            else
                                                echo '<b>No Score</b>';
                                        else
                                            echo '<b>No Score</b>';
                                        ?>
                                    </td> 
                                </tr> 
                                <tr> 
                                    <td style="text-align: right;"><b>Fixture New Requested Score</b></td>
                                    <td></td> 
                                </tr> 
                                <tr> 
                                    <td>Player 1 Score</td>
                                    <td><b>{{ $tournamentFixtureByReportId->player_id_1_score }}</b></td> 

                                </tr> 
                                <tr> 
                                    <td>Player 2 Score</td>
                                    <td><b>{{ $tournamentFixtureByReportId->player_id_2_score }}</b>
                                    </td> 
                                </tr> 

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }
</script>
@endsection
