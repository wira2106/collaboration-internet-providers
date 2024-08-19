@extends('layouts.master')

@section('content-header')
@stop

@section('content')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('configuration::configurations.title.create configuration') }}</dd>
    </dl>
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
        //  
    </script>
@endpush
