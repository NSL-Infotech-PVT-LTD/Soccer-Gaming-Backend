@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')
        <a href="{{ url('/admin/banner/create') }}" class="btn btn-success btn-sm" title="Add New Banner" style="margin-left: 14px">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New
        </a>
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">Banner</div><br>
                <div class="table-responsive">
                    <table class="table table-borderless data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <?php foreach ($rules as $rule): ?>
                                    <th>{{ucfirst($rule)}}</th>
                                <?php endforeach; ?>
                                <th>Action</th>
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
            ajax: "{{ route('banner.index') }}",
            columns: [
            {data: 'id', name: 'id'},
<?php foreach ($rules as $rule): ?>
    <?php if ($rule == 'image'): ?>
                    {data: 'image', name: 'image', orderable: false, searchable: false},
    <?php else: ?>
                    {data: "{{$rule}}", name: "{{$rule}}"},
    <?php endif; ?>
<?php endforeach; ?>
            {data: 'action', name: 'action', orderable: false, searchable: false}
            ,
            ]
    });
//deleting data
    }
    );
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
            location.reload();
            swal.fire("Deleted!", "Banner has been deleted", "success");
            table.ajax.reload(null, false);
            }
            }
    });
    }
    });
    });
</script>

@endsection
