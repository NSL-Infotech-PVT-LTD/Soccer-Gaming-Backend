@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Player</div>
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
                                <th>Profile Image</th>
                                <td><img width="60" src="<?= url('uploads/image/' . $user->image); ?>" style="
                                         margin-bottom: 4px;"></td>
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
                                <th>Created at</th>
                                <td> {{ $user->created_at }} </td>
                            </tr>

                            <?php if ($user->xbox_id != null): ?>
                                <tr>
                                    <th>xbox Id</th>
                                    <td> {{ $user->xbox_id }} </td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($user->ps4_id != null): ?>
                                <tr>
                                    <th>ps4 Id</th>
                                    <td> {{ $user->ps4_id }} </td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($user->youtube_id != null): ?>
                                <tr>
                                    <th>Youtube Id</th>
                                    <td>{{ $user->youtube_id }}</td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($user->twitch_id != null): ?>
                                <tr>
                                    <th>Twitch Id</th>
                                    <td>{{ $user->twitch_id }}</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <?php
            $playerfriends = new \App\UserFriend();
            $playerfriends = $playerfriends->select('id', 'user_id', 'friend_id', 'status', 'params', 'state');
            $id = $user->id;
//            dd($id);
            $playerfriends = $playerfriends->where(function($query) use ($id) {
                $query->where('user_id', $id);
                $query->orWhere('friend_id', $id);
            });
            $playerfriends = $playerfriends->where("status", "accepted")->get();
//            dd($playerfriends);
            ?>        
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
                                <?php $i = 1; ?>
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
