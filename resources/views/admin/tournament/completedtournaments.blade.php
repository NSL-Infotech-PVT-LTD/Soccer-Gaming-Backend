@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
            <div class="card">
                <div class="card-header">Tournaments history</div>
                <div class="card-body">
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="mytable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Tournament Name</th><th>Number of players</th><th>Author</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedtournaments as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->number_of_players}}</td>
                                    
                                    <td><?php
                                        $user = DB::table('users')->where('id', $item->created_by)->get();
                                        if($user->isEmpty()!=true)
                                             if($user->first()->username == ''){
                                                echo $user->first()->first_name .' '.$user->first()->last_name;
                                             }else{
                                                 echo $user->first()->username;
                                             }
                                        else
                                            echo $item->created_by;
                                        ?>
                                    </td>
                                    <td>
                                        <a href="{{url('/admin/tournament/' . $item->id)}}" title='View Tournament'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th><th>Tournament Name</th><th>Number of players</th><th>Author</th><th>Action</th>
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
    $(function () {
        $(document).ready(function () {
            var table = $('.mytable').DataTable();
        });
        $('.mytable').on('click', '.changeStatus', function (e) {

            e.preventDefault();
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            Swal.fire({
                title: 'Are you sure you want to Unreport?',
                text: "Once done cannot be undone",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + status + ' it!'
            }).then((result) => {
                Swal.showLoading();
                if (result.value) {
                    var form_data = new FormData();
                    form_data.append("id", id);
                    form_data.append("status", status);
                    form_data.append("_token", $('meta[name="csrf-token"]').attr('content'));
                    $.ajax({
                        url: "{{route('tournament.changeStatus')}}",
                        method: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            //                        Swal.showLoading();
                        },
                        success: function (data)
                        {
                            Swal.fire(
                                    status + ' !',
                                    'Tournament has been unreported .',
                                    'success'
                                    ).then(() => {
                                table.ajax.reload();
                            });
                        }
                    });
                    location.reload();
                }
            });
        });
    });
</script>
@endsection
