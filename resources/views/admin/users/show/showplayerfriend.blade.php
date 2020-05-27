@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Player Friends</div>
                <div class="card-body">
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="mytable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Username</th><th>First name</th><th>Last Name</th><th>Email</th>
                                    <!--<th>Actions</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i =1; ?>
                                @foreach($playerfriends as $item)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        <?php
                                        $user = DB::table('users')->where('id', $item->friend_id)->first();
                                        echo $user->username;
                                        ?>
                                    </td>
                                    <td>{{ $user->first_name }}</td>
                                    <td>{{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
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
                                    <th>#</th><th>Username</th><th>First name</th><th>Last Name</th><th>Email</th>
                                    <!--<th>Actions</th>-->
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
