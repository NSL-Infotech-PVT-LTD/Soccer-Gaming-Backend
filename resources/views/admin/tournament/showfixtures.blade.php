@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <a href="{{ url(url()->previous()) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
            <div class="card">
                <div class="card-header">Tournament Reports</div>
                <div class="card-body">
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="mytable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Tournament Name</th><th>Type</th><th>Author</th><th>Reported By</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournamentfixtures as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>
                                        <?php
                                        $tournament = DB::table('tournaments')->where('id', $item->tournament_id)->get();
                                        if($tournament->isEmpty()!=true)
                                            echo $tournament->first()->name;
                                        else
                                            echo $item->tournament_id;
                                        ?>
                                    </td>
                                    <td><?=($tournament->first()->type != null) ? $tournament->first()->type : "-" ?></td>
                                    <td><?php
                                        $user = DB::table('users')->where('id', $item->tournament_created_by_id)->get();
                                        if($user->isEmpty()!=true)
                                            echo $user->first()->username;
                                        else
                                            echo $item->tournament_created_by_id;
                                        ?>
                                    </td>
                                    <td><?php
                                        $reportedBy = DB::table('users')->where('id', $item->created_by)->get();
                                        if($reportedBy->isEmpty()!=true)
                                            echo $reportedBy->first()->username;
                                        else
                                            echo $item->created_by;
                                        ?>
                                    </td>
                                    <td><?=($item->status == null) ? "<span style='color:orange;'>Pending</span>" : "<span style='color:green;'>$item->status</span>" ?></td>

<!--                                    <td>
                                        <?= ($item->state == '0') ? '' : "&nbsp;<a href=" . url('admin/editfixture/' . $item->id) . " style='color:red; text-decoration:none;position:absolute;' title = 'Edit Fixture'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
</a>" ?>
                                    </td>-->
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
                                    <th>#</th><th>Tournament Name</th><th>Type</th><th>Author</th><th>Reported By</th><th>Status</th>
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
