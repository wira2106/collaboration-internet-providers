<?php

namespace Modules\Saldo\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Saldo\Entities\MutasiSaldo;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Saldo\Transformers\ListMutasiSaldoTransformer;
use Modules\Saldo\Transformers\SaldoAwalAkhirTransformer;
use DB;

class mutasiController extends AdminBaseController
{
     public function pagination(Request $request) {
        $mutasi  = new MutasiSaldo;        
        $company_id = $request->get("company_id");
        if ($company_id == null && $company_id == "") {
            $comp = Auth::User()->company();
            $company_id = $comp->company_id;
        }

        if ($request->periode == null || $request->periode == "") {
          $periode = date('Y-m');
        }else{
          $periode = $request->periode;
        }

        $params = [
          'company_id' => $company_id,
          'periode' => $periode
        ];
        
        $data = $this->serverPaginationFilteringFor($params,$request);
        $first = $data->first();
        if ($first != null) {
          $saldoAkhirList = $mutasi->getMutasiById($first->mutasi_id,$company_id)->saldo;          
        }else{
          $saldoAkhirList = 0;
        }
        $selected = [
                      "company_select" => $company_id,
                      "periode_select" => $periode,
                      "saldoAkhirList" => $saldoAkhirList 
                    ];

        return ListMutasiSaldoTransformer::collection($data)->additional($selected);
    }

    public function serverPaginationFilteringFor($params, Request $request)
    {
        $data = MutasiSaldo::select(['mutasi_saldo.*'])
                ->join('saldo','saldo.saldo_id','=','mutasi_saldo.saldo_id')      
                ->where('saldo.company_id',$params['company_id']);        
        
        $data = $data->where('created_at','like',"%".$params['periode']."%");

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $data->where('deskripsi', 'LIKE', "%{$term}%");
        }
        
        $data->orderBy('mutasi_id', 'desc');
        $data = $data->paginate($request->get('per_page', 10));
        return  $data;
    }

    public function getSaldo(Request $req)
    {
        $periode = date('Y-m');
        if ($req->periode != "" && $req->periode != null) {
          $periode = $req->periode;
        }
        $company_id = $req->get("company_id");
        if ($company_id == null && $company_id == "") {
            $comp = Auth::User()->company();
            $company_id = $comp->company_id;
        }

        $model = new MutasiSaldo;
        $data = $model->getSaldoAwalAkhir($periode,$company_id);
        
        return new SaldoAwalAkhirTransformer($data);
    }

}
