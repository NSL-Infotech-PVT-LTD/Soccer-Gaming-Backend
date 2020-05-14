@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tournament</div>
                <div class="card-body">


                    {!! Form::open(['method' => 'GET', 'url' => '/admin/tournamentFixture', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search'])  !!}
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <span class="input-group-append">
                            <button class="btn btn-secondary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                    {!! Form::close() !!}

                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>#</th><th>Player1 Name</th><th>Player1 Score</th><th>Player2 Name</th><th>Player2 Score</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournamentfixtures as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <?php
                                        $user = DB::table('users')->where('id', $item->player_id_1)->first();
                                        echo $user->username;
                                        ?>
                                    </td><td>{{ $item->player_id_1_score }}</td>
                                    <td>
                                        <?php
                                        $user = DB::table('users')->where('id', $item->player_id_2)->first();
                                        echo $user->username;
                                        ?>
                                    </td>
                                    <td>{{ $item->player_id_2_score }}</td>
                                    <td>

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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
