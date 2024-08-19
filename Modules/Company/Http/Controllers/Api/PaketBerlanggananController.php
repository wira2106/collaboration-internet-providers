<?php

namespace Modules\Company\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\Company\Repositories\PaketBerlanggananRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\PaketForIsp;
use Modules\Company\Events\PaketIsCreated;
use Modules\Company\Events\PaketIsDestroy;
use Modules\Company\Events\PaketIsUpdated;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Company\Transformers\PaketBerlanggananTransformer;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Wilayah\Entities\Wilayah;

class PaketBerlanggananController extends AdminBaseController
{
    /**
     * @var PaketBerlanggananRepository
     */
    private $paketberlangganan;

    public function __construct(PaketBerlanggananRepository $paketberlangganan)
    {
        parent::__construct();

        $this->paketberlangganan = $paketberlangganan;
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $company = new Company;
        $user = Auth::user()->hasRoleName('Super Admin');

        $id_company = Auth::user()->company();
        if ($request->id_company == null) {
            if ($user) {
                $id_company = Company::where('type', 'osp')->first();
            }
        } else {
            $id_company = Company::find($request->id_company);
        }

        $data = $company->getPaket($request, 'osp', $id_company);
        $osp = [
            'osp' => $company->getIspOsp('osp'),
            'id' =>  $id_company->company_id
        ];
        return PaketBerlanggananTransformer::collection($data)
            ->additional($osp);
    }

    public function option(Request $request)
    {
        $company = new Company;
        $osp      = $company->getIspOsp('osp');
        return compact('osp');
    }

    public function store(Request $request)
    {
        $user   = Auth::user();
        if ($user->hasRoleName('Super Admin')) {
            $company_id = $request->company_id;
        } else {
            $company_id = $user->company()->company_id;
        }

        $data = [
            'company_id' => $company_id,
            'nama_paket' => $request->nama_paket,
            'biaya_otc' => $request->biaya_otc,
            'harga_paket' => $request->harga_paket,
            'sla' => $request->sla,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user->id
        ];

        $paket_berlangganan = PaketBerlangganan::create($data);

        event(new PaketIsCreated($paket_berlangganan, $data));

        return response()->json([
            'errors' => false,
            'message' => trans('company::paketberlangganans.messages.paket save')
        ]);
    }

    public function find(PaketBerlangganan $paket)
    {
        return PaketBerlangganan::where('paket_id', $paket->paket_id)->first();
    }

    public function update(PaketBerlangganan $paket, Request $request)
    {
        $user   = Auth::user();
        $data = [
            'nama_paket' => $request->nama_paket,
            'biaya_otc' => $request->biaya_otc,
            'harga_paket' => $request->harga_paket,
            'sla' => $request->sla,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $user->id
        ];


        $paket->fill($data);
        $paket->save();

        event(new PaketIsUpdated($paket, $data));
        return response()->json([
            'errors' => false,
            'message' => trans('company::paketberlangganans.messages.paket save')
        ]);
    }

    public function destroy(PaketBerlangganan $paket)
    {
        $paket->delete();

        event(new PaketIsDestroy($paket));
        return response()->json([
            'errors' => false,
            'message' => trans('company::paketberlangganans.messages.paket delete')
        ]);
    }

    public function list($company)
    {
        $data = PaketBerlangganan::where("company_id", $company->company_id)->get();
        return PaketBerlanggananTransformer::collection($data);
    }

    public function list_paket_wilayah(Wilayah $wilayah)
    {
        return $wilayah->paket_berlangganan();
    }
}
