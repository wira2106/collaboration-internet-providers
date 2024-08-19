@extends('layouts.master')

@section('content-header')
@stop

@section('content')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')

@stop

@push('js-stack')
    <script type="text/javascript">
        // $( document ).ready(function() {
        //     $(document).keypressAction({
        //         actions: [
        //             { key: 'c', route: "<?= route('admin.configuration.configuration.create') ?>" }
        //         ]
        //     });
        // });
    </script>
    <?php $locale = locale(); ?>
    <script type="text/javascript">
        // $(function () {
        //     $('.data-table').dataTable({
        //         "paginate": true,
        //         "lengthChange": true,
        //         "filter": true,
        //         "sort": true,
        //         "info": true,
        //         "autoWidth": true,
        //         "order": [[ 0, "desc" ]],
        //         "language": {
        //             "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
        //         }
        //     });
        // });
    </script>
    <!-- <script>
        var link = "{{url('/alat/view')}}";
    </script>
    <script src="{{url('js/peralatan/alat.js')}}"></script> -->

@endpush

