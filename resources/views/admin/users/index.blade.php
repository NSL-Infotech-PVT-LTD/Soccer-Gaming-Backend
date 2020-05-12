@extends('layouts.backend')
<style>

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }


</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        @include('admin.sidebar')
        <div class="col-md-12">
            <div class="card">
                <?php
                $user = \App\Role::whereId($role_id)->first()->name . ' User';
                ?>
                <div class="card-header  text-center"><h3 >{{ucfirst($user)}}</h3></div>
                <div class="card-body">
                    <?php if (isset($role_id)): ?> 
                       
<!--                            <a href="{{ url('/admin/users/create') }}" class="btn btn-success btn-sm" title="Add New User">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>-->
                       
                    <?php endif; ?>

                </div>

                <br/>
                <br/>
                <div class="table-responsive">
                    <table class="table table-borderless data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <?php foreach ($rules as $rule): ?>
                                    <th>{{ucfirst($rule)}}</th>
                                <?php endforeach; ?>
                                <th>Actions</th>


                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
        processing: true,
                serverSide: true,
                ajax: "{{ route('users-role',$role_id) }}",
                columns: [
                {data: 'id', name: 'id'},
<?php foreach ($rules as $rule): ?>
    <?php if ($rule == 'email'): ?>
                        {data: 'email', name: 'email', orderable: false, searchable: false},
    <?php else: ?>
                        {data: "{{$rule}}", name: "{{$rule}}"},
    <?php endif; ?>
<?php endforeach; ?>
                {data: 'action', name: 'action', orderable: false, searchable: false}
                ,
                ]
    });
//deleting data
    $('.data-table').on('click', '.btnDelete[data-remove]', function (e) {
        e.preventDefault();
        var url = $(this).data('remove');
        swal.fire({
            title: "Are you sure want to remove this item?",
            text: "Data will be Temporary Deleted!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
        }).then((result) => {
            Swal.showLoading();
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {method: '_DELETE', submit: true, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        if (data == 'Success') {
                            swal.fire("Deleted!", "User has been deleted", "success");
                            table.ajax.reload(null, false);
                        }
                    }
                });
            }
        });
    });
  $('.data-table').on('click', '.changeStatus', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            Swal.fire({
                title: 'Are you sure you want to change status?',
                text: "You can revert this,in case you change your mind!",
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
                        url: "{{route('user.changeStatus')}}",
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
                                    'User has been ' + status + ' .',
                                    'success'
                                    ).then(() => {
                                table.ajax.reload(null, false);
                            });
                        }
                    });
                }
            });

        });

    });
    
</script>
@endsection
