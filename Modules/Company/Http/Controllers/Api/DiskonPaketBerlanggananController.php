<?php

namespace Modules\Company\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Entities\PaketBerlangganan;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\DiskonPaketBerlangganan;
use Modules\Company\Entities\DiskonPaketBerlanggananTranslation;
use Modules\Company\Events\DiskonPaketIsCreated;
use Modules\Company\Events\DiskonPaketIsDestroy;
use Modules\Company\Events\DiskonPaketIsUpdated;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Company\Transformers\DiscountPaketBerlanggananTransformer;
use Modules\Company\Transformers\DiskonPaketTransformer;



class DiskonPaketBerlanggananController extends AdminBaseController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }
    public function list($paket_id)
    {
        $diskon = PaketBerlangganan::find($paket_id)->getAllDiscount();
        return DiskonPaketTransformer::collection($diskon);
    }

    public function index(Request $request, $id)
    {
        $paket = new PaketBerlangganan;
        return DiscountPaketBerlanggananTransformer::collection($paket->getDiscount($request, $id));
    }

    public function store(Request $request, $id)
    {
        $data = [
            'paket_id' => $id,
            'diskon' => $request->diskon,
            'start_pelanggan' => $request->start_pelanggan,
            'end_pelanggan' => $request->end_pelanggan
        ];
        $diskon_paket = DiskonPaketBerlangganan::create($data);
        
        event(new DiskonPaketIsCreated($diskon_paket));
        return response()->json([
            'errors' => false,
            'message' => trans('company::paketberlangganan.messages.paket save')
        ]);
    }

    public function find($id)
    {
        return DiskonPaketBerlangganan::find($id);
    }

    public function update(DiskonPaketBerlangganan $diskon, Request $request)
    {
        $diskon->fill($request->all());
        
        event(new DiskonPaketIsUpdated($diskon));
        
        $diskon->save();
        

        
        return response()->json([
            'errors' => false,
            'message' => trans('company::paketberlangganan.messages.paket save')
        ]);
    }

    public function destroy(DiskonPaketBerlangganan $diskon)
    {
        $diskon->delete();
        event(new DiskonPaketIsDestroy($diskon));

        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::barangs.messages.barang deleted')
        ]);
    }
}
