@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tournaments</div>
                <br>

                <div class="card-body">
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
</div>
<script type="text/javascript">
    $(function () {
    var table = $('.data-table').DataTable({
    processing: true,
            serverSide: true,
            ajax: "{{ route('tournament.index') }}",
            columns: [
            {data: 'id', name: 'id'},
<?php foreach ($rules as $rule): ?>
    <?php if ($rule == 'type'): ?>
                    {data: 'type', name: 'type', orderable: false, searchable: false},
    <?php elseif ($rule == 'number_of_players'): ?>
                    {data: 'number_of_players', name: 'number_of_players', orderable: false, searchable: false,  className: "text-center"},
    <?php elseif ($rule == 'number_of_teams_per_player'): ?>
                    {data: 'number_of_teams_per_player', name: 'number_of_teams_per_player', orderable: false, searchable: false, className: "text-center"},
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
</script>
@endsection
