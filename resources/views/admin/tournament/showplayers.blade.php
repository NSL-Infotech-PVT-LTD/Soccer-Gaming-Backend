@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
            <div class="card">

                <div class="card-header">Tournament Players</div>
                <div class="card-body">
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="mytable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Tournament Name</th><th>Player Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($tournamentPlayers as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>

                                    <td>
                                        <?php
                                        $tournament = DB::table('tournaments')->where('id', $item->tournament_id)->first();
                                        echo $tournament->name;
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        $user = DB::table('users')->where('id', $item->player_id)->first();
                                        echo $user->username;
                                        ?>
                                    </td>
                                    <td><a href="{{url('/admin/playerFixtures/' . $item->player_id)}}" title='View Fixtures'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a></td>
<!--                                    <td>

                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'url' => ['/admin/tournament', $item->id],
                                        'style' => 'display:inline'
                                        ]) !!}
                                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-sm',
                                        'title' => 'Delete Tournament',
                                        'onclick'=>'return confirm("Confirm delete?")'
                                        )) !!}
                                        {!! Form::close() !!}
                                    </td>-->
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th><th>Tournament Name</th><th>Player Name</th>
                                    <th>Actions</th>
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
        $('.mytable').DataTable();
    });
</script>
@endsection
