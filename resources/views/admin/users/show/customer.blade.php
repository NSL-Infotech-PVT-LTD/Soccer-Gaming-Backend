@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">User</div>
                    <div class="card-body">

                         <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
<!--                        <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" title="Edit User"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>-->
<!--                        {!! Form::open([
                            'method' => 'DELETE',
                            'url' => ['/admin/users', $user->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete User',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}-->
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                
                                    <tr>
                                        <th>ID.</th>
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Username</th>
                                        <td>{{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <th>First Name</th>
                                        <td> {{ $user->first_name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Last Name</th>
                                        <td> {{ $user->last_name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td> {{ $user->email }} </td>
                                    </tr>
                                    <tr>
                                        <th>Field to play</th>
                                        <td> {{ $user->field_to_play }} </td>
                                    </tr>
                                    <tr>
                                        <th>Field to play ID</th>
                                        <td> {{ $user->field_to_play_id }} </td>
                                    </tr>
                                    <tr>
                                        <th>Video stream</th>
                                        <td>{{ $user->video_stream }}</td>
                                    </tr>
                                    <tr>
                                        <th>Video stream Id</th>
                                        <td>{{ $user->video_stream_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Image</th>
                                        <td><img width="50" src="<?= url('uploads/image/'.$user->image); ?>" style="
                                             margin-bottom: 4px;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
