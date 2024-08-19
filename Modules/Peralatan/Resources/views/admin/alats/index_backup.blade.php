@extends('layouts.master')

@section('content-header')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">  {{ trans('peralatan::alats.title.alats') }} </h3> 
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"> <i class="fa fa-dashboard"></i>{{ trans('core::core.breadcrumb.home') }}</a></li>
                <li class="breadcrumb-item"> {{ trans('peralatan::alats.title.alats') }} </li>
            </ol>
        </div>
        <div class="">
            <button class="right-side-toggle waves-effect waves-light btn-inverse btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
        </div>
    </div>
@stop

@section('content')
    <div class="container">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.peralatan.alat.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-edit"></i> {{ trans('peralatan::alats.button.create alat') }}
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Alat</h3>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                    <div class="row">
                         <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" value="" id="search" placeholder=
                                "cari">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary btn-sm" onClick="view()"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                         </div>
                        <div class="col-md-6">
                            <div class="mailbox-controls">
                                <div class="float-right">                    
                                    <span id="displayPage">0-0/0</span>
                                    <div class="btn-group" id="btnPaging1">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover small">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Alat</th>                        
                                        <th>Created</th>
                                        <th>Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyListAlat">
                                    @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{$key+1}}.</td>
                                        <td>
                                            {{$value->nama_alat}}
                                        </td>
                                        <td>
                                            {{$value->created_at}}
                                        </td>
                                        <td>
                                            {{$value->updated_at}}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.peralatan.barang.edit', [$value->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-edit"></i></a>
                                                <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.peralatan.barang.destroy', [$value->id]) }}"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mailbox-controls">
                                <div class="float-right">                    
                                    <span id="displayPage2">0-0/0</span>
                                    <div class="btn-group" id="btnPaging2">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
        </div>
    </div>
    @include('core::partials.delete-modal')
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
    <script>
        var link = "{{url('/alat/view')}}";
    </script>
    <script src="{{url('js/peralatan/alat.js')}}"></script>

@endpush

