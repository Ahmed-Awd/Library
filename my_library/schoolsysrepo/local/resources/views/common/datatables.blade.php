<script src="{{JS}}bootstrap-toggle.min.js"></script>
<script src="{{JS}}jquery.dataTables.min.js"></script>
<script src="{{JS}}dataTables.bootstrap.min.js"></script>

<?php    $routeValue = $route; ?>

@if(!isset($route_as_url))
    {
    <?php $routeValue = route($route); ?>
    }
@endif

<?php
$setData = array();
if (isset($table_columns)) {
    foreach ($table_columns as $col) {
        $temp['data'] = $col;
        $temp['name'] = $col;
        array_push($setData, $temp);
    }
    $setData = json_encode($setData);
}
?>

@if(isset($extra_var))
    @if($extra_var==1)
        <script>

            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    type: 'GET',
                    order: [ [0, 'desc'] ],

                    ajax: '{{ $routeValue }}',
                    @if(isset($user_type))
                            @if($user_type=='student')
                    columns: [
                        {data: 'roll_no', name: 'roll_no'},
                        {data: 'image', name: 'users.image'},
                        {data: 'first_name', name: 'first_name'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'email', name: 'users.email'}
                    ],
                    @else
                    columns: [
                        {data: 'staff_id', name: 'staff_id'},
                        {data: 'image', name: 'users.image'},
                        {data: 'first_name', name: 'first_name'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'email', name: 'users.email'}
                    ]
                    @endif
                    @endif
                });
            });

        </script>
    @endif
@else
    <script>

        var tableObj;

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            tableObj = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                cache: true,
                type: 'GET',
                "pageLength": 25,
                "language": {
                    "emptyTable": "{{getPhrase('no_data_available_in_table')}}",
                    "paginate": {
                        "next": "{{getPhrase('next')}}",
                        "previous": "{{getPhrase('previous')}}"
                    },
                    "search": "{{getPhrase('search')}}",
                    "lengthMenu": "{{getPhrase('show')}} _MENU_ {{getPhrase('entries')}}",
                    "info":"{{getPhrase('showing')}} _START_ {{getPhrase('to')}} _END_ {{getPhrase('of')}} _TOTAL_ {{getPhrase('entries')}}",
                    "infoEmpty":"{{getPhrase('showing')}} 0 {{getPhrase('to')}} 0 {{getPhrase('of')}} 0 {{getPhrase('entries')}}",
                },
                ajax: '{{ $routeValue }}',
                order: [ [0, 'desc'] ],
                @if(isset($table_columns))
                columns: "{!!$setData!!}"
                @endif
            });
        });
    </script>
@endif