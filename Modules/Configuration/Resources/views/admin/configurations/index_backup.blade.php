@extends('layouts.master')

@section('content-header')
        
@stop

@section('content')
    <div class="container">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.configuration.configuration.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-edit"></i> {{ trans('configuration::configurations.button.create configuration') }}
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Setting</h3>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                    {!! Form::open(['route' =>['admin.configuration.configuration.update',1], 'method'=>'put']) !!}
                         @foreach($data as $data)
                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-md-4">
                                            <label>Admin Fee</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" name="admin_fee" value="{{$data->admin_fee}}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pl-0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-md-4">
                                            <label>Persentase OTC_MRC</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" name="persentase_otc_mrc" value="{{$data->persentase_otc_mrc}}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pl-0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-md-4">
                                            <label>Persentase Refund Openaccess</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" name="persentase_refund_osp" value="{{$data->persentase_refund_osp}}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pl-0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-md-4">
                                            <label>Persentase Refund OSP</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" name="persentase_refund_openaccess" value="{{$data->persentase_refund_openaccess}}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pl-0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-md-4">
                                            <label>SLA ODP</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" name="sla_odp" value="{{$data->sla_odp}}">
                                                <span class="input-group-text" style="">hari</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pl-0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-md-4">
                                            <label>SLA Join Box</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" name="sla_join_box" value="{{$data->sla_join_box}}">
                                                <span class="input-group-text" style="">hari</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pl-0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-md-4">
                                            <label>SLA Fixing Slack</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" name="sla_fixing_stack" value="{{$data->sla_fixing_stack}}">
                                                <span class="input-group-text" style="">hari</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pl-0">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-md btn-primary">Simpan</button>
                                    </div>
                                </div>
                    {!! Form::close() !!}
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
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.configuration.configuration.create') ?>" }
                ]
            });
        });
    </script>
    <?php $locale = locale(); ?>
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
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
@endpush
