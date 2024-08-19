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
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script type="text/javascript">
        // $( document ).ready(function() {
        //     $(document).keypressAction({
        //         actions: [
        //             { key: 'b', route: "<?= route('admin.peralatan.alat.index') ?>" }
        //         ]
        //     });
        // });
    </script>
    <script>
        // $( document ).ready(function() {
        //     $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        //         checkboxClass: 'icheckbox_flat-blue',
        //         radioClass: 'iradio_flat-blue'
        //     });
        // });
    </script>
    <script>
        var alatInput = `
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Nama Alat" aria-label="" aria-describedby="basic-addon2" name="admin_fee" value="">
                            <button type="button" onclick="hapus()" class="input-group-text btn btn-danger"><i class="fa fa-minus"></i></button>
                        </div>
                        `;
    </script>
    <script src="{{url('js/peralatan/tambah.js')}}"></script>
@endpush
