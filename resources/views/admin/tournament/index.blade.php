@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tournament</div>
                <div class="card-body">
                    <a href="{{ url('/admin/tournament/create') }}" class="btn btn-success btn-sm" title="Add New Tournament">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a>

                    {!! Form::open(['method' => 'GET', 'url' => '/admin/tournament', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search'])  !!}
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
                                    <th>#</th><th>Name</th><th>Type</th><th>Number Of Players</th><th>Number of teams per Players</th><th>Number of Plays</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournament as $item)
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td>{{ $item->name }}</td><td>{{ $item->type }}</td><td>{{ $item->number_of_players }}</td><td>{{ $item->number_of_teams_per_player }}</td><td>{{ $item->number_of_plays_against_each_team }}</td>
                                    <td>
                                        <a href="{{ url('/admin/tournament/' . $item->id) }}" title="View Tournament"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/admin/tournamentFixture/' . $item->id) }}" title="View Tournament Fixture"><button class="btn btn-info btn-sm"><i class="fa fa-gamepad" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/admin/tournament/' . $item->id . '/edit') }}" title="Edit Tournament"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
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
                        <div class="pagination-wrapper"> {!! $tournament->appends(['search' => Request::get('search')])->render() !!} </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
