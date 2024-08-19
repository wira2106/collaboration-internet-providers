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
    $(document).ready(function() {
        $(document).keypressAction({
            actions: [{
                key: 'c',
                route: "<?= route('admin.peralatan.barang.create') ?>"
            }]
        });
    });
</script>
<?php $locale = locale(); ?>

@endpush