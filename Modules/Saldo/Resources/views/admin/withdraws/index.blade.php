@extends('layouts.master')

@section('content-header')

@stop

@section('content')

@stop

@section('footer')
@stop
@section('shortcuts')

@stop

@push('js-stack')
<!-- <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.saldo.withdraw.create') ?>" }
                ]
            });
        });
    </script>
    <script type="text/javascript">
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": 
                }
            });
        });
    </script> -->
@endpush