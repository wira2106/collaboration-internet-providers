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
<!-- <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.company.paketberlangganan.create') ?>" }
                ]
            });
        });
    </script> -->
<?php $locale = locale(); ?>
@endpush