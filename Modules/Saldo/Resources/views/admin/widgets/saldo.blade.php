<div class="col-md-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                
                <div class="col-12">
                    <h2 class="m-b-0"><i class="mdi mdi-wallet text-purple"></i></h2>
                    <h3 class="">{{ $saldo }}</h3>
                    
                    @if($dibekukan)
                    <h5 class=" text-muted">{{ trans('saldo::saldos.dibekukan') }} {{ $dibekukan }}</h5>
                    @else
                    <h6 class="card-subtitle">{{ trans('saldo::saldos.title.saldo') }}</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>