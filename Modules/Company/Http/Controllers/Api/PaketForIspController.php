<?php

namespace Modules\Company\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\PaketForIsp;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\Company\Entities\PaketBerlanggananTranslation;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Company\Transformers\PaketBerlanggananTransformer;



class PaketForIspController extends AdminBaseController
{

    public function index(Request $request)
    {
        $company = new Company;
        $id_company = Auth::user()->company();

        return PaketBerlanggananTransformer::collection($company->getPaket($request, 'isp', $id_company));
    }

    public function store(Request $request)
    {
        $paket_isp = DB::table('paket_for_isp');
        foreach ($request->paket_id as $key => $value) {
            if (!($paket_isp->where('isp_id', '=', $request->isp_id)->exists()
                && $paket_isp->where('paket_id', '=', $request->paket_id[$key])->exists())) {
                $paket_isp->insert([
                    'isp_id' => $request->isp_id,
                    'paket_id' => $request->paket_id[$key]
                ]);
            }
        }

        return response()->json([
            'errors' => false,
            'message' => trans('company::paketberlangganan.messages.paket save')
        ]);
    }

    public function option(Request $request)
    {
        $company = new Company;
        $id_company = Auth::user()->company();
        $paket    =  $company->getPaket($request, 'option', $id_company);
        $isp      = $company->getIspOsp('isp');
        return compact('paket', 'isp');
    }

    public function update(PaketBerlangganan $paket, Request $request)
    {
        $user   = Auth::user();
        PaketBerlangganan::where('paket_id',  $paket->paket_id)
            ->update([
                'nama_paket' => $request->nama_paket,
                'biaya_otc' => $request->biaya_otc,
                'harga_paket' => $request->harga_paket,
                'sla' => $request->sla,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $user->id
            ]);
        return response()->json([
            'errors' => false,
            'message' => trans('company::paketberlangganan.messages.paket save')
        ]);
    }
}
