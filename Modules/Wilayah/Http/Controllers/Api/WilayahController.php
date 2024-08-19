<?php

namespace Modules\Wilayah\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Wilayah\Entities\Wilayah;
use Modules\Presale\Entities\Presale;
use Modules\Wilayah\Http\Requests\CreateWilayahRequest;
use Modules\Wilayah\Http\Requests\UpdateWilayahRequest;
use Modules\Wilayah\Repositories\WilayahRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Utils\Http\Controllers\UtilsController;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Company;
use Modules\Wilayah\Events\WilayahIsCreated;
use Modules\Wilayah\Events\WilayahIsDestroy;
use Modules\Wilayah\Events\WilayahIsUpdated;
use Modules\Wilayah\Transformers\WilayahTransformer;
use Modules\Wilayah\Transformers\ListWilayahTransformer;
use Modules\Wilayah\Transformers\WilayahInfoPengajuan;
use Modules\Wilayah\Transformers\WilayahDetailPengajuan;


class WilayahController extends AdminBaseController
{
    /**
     * @var WilayahRepository
     */
    private $wilayah;

    public function __construct(WilayahRepository $wilayah)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
        $this->wilayah = $wilayah;
    }

    public function submitFormWilayah(Wilayah $wilayah, Request $req)
    {
        DB::beginTransaction();
        $data = $req->all();
        $isNew = $wilayah->wilayah_id == null;
        if (!$data['company_id']) $data['company_id'] = Auth::user()->userCompanies->company_id;
        $wilayah->fill($data);
        if ($isNew) {
            $wilayah->created_by = Auth::User()->id;
            event( $event = new WilayahIsCreated( $wilayah, $data ) );
        } else {
            $wilayah->updated_by = Auth::User()->id;
            event( $event = new WilayahIsUpdated( $wilayah, $data ) );
        }

        $wilayah->save();

        if($isNew) $wilayah->add_new_endpoint($req->endpoint, $req->bayar);

        DB::commit();
        return response()->json([
            'errors' => false,
            'message' => $isNew ? trans('wilayah::wilayahs.messages.wilayah created') : trans('wilayah::wilayahs.messages.wilayah updated'),
        ]);
    }

    public function find($wilayah, Request $req)
    {
        $detail = (int)$req->detail;
        $wilayah = Wilayah::select(["wilayah.*", "companies.name as company_name"])
            ->join("companies", "companies.company_id", "=", "wilayah.company_id")->find($wilayah);
        if ($detail == 1) {
            $wilayah->detailWilayah();
            return new WilayahInfoPengajuan($wilayah);
        } else if ($detail == 2) {
            $wilayah->detailWilayah();
            $wilayah->getParticipant();
            return new WilayahDetailPengajuan($wilayah);
        } else {
            // $wilayah->hasAccess(Auth::user());
            return new WilayahTransformer($wilayah);
        }
    }

    public function pagination(Request $request)
    {
        $company  = new Company;
        $company_id = $request->get("company_id");
        if (!Auth::User()->hasRoleName('Super Admin')) {
            $comp = Auth::User()->company();
            $company_id = $comp->company_id;
        }

        $data = $this->serverPaginationFilteringFor($company_id, $request);
        $allDataWilayah = [
            'companies' => $company->getAllOSP(Auth::user()), 
            "selected" => $company_id
        ];

        return ListWilayahTransformer::collection($data)->additional($allDataWilayah);
    }

    public function isp(Request $request) {
        if(!Auth::user()->hasRoleName('Admin ISP')) {
            return abort(403);
        }

        $data = (new Wilayah())->serverPaginationFilteringForISP($request);
        return ListWilayahTransformer::collection($data);
    }

    public function serverPaginationFilteringFor($company_id, Request $request)
    {
        $wilayah = Wilayah::select(['wilayah.*', 'companies.name as nama_company'])
            ->join('companies', 'companies.company_id', '=', 'wilayah.company_id');
        if ($company_id != null && $company_id != "") {
            $wilayah->where('wilayah.company_id', $company_id);
        }

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $wilayah->where(function ($query) use ($term) {
                $query->orWhere('companies.name', 'LIKE', "%{$term}%")
                    ->orWhere('wilayah.name', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $wilayah->orderBy($request->get('order_by'), $order);
        } else {
            $wilayah->orderBy('wilayah_id', 'asc');
        }
        return $wilayah->paginate($request->get('per_page', 10));
    }

    

    public function deleteWilayah(Wilayah $wilayah)
    {
        $wilayah->delete();

        event(new WilayahIsDestroy($wilayah));

        return response()->json([
            'errors' => false,
            'message' => trans("wilayah::wilayahs.messages.wilayah deleted")
        ]);
    }

    public function loadTitik(Request $req)
    {
        $model = new Wilayah();
        $wilayah = $model->listWilayahMap();
        $send = [];
        foreach ($wilayah as $key => $value) {
            $site = DB::table('presales')
                    ->join('active_presales', 'presales.presale_id', 'active_presales.presale_id')
                    ->where('wilayah_id', $value->wilayah_id)->where('status_id', 1)
                    ->count();
            $point = $this->getMiddlePoint($value->wilayah_id);
            if ($point && $site > 0) {
                $value->latLng = $point;
                $value->site = $site;
                $send[] = $value;
            }
        }

        return response()->json($send);
    }

    function getMiddlePoint($wilayah_id)
    {
        $latLon = ["lat", "lon"];
        $mode = ["start", "end"];
        $point = [];
        $check = Presale::select(['lat'])->where("wilayah_id", $wilayah_id)->where("deleted_at", null)->first();
        if ($check != null) {
            for ($x = 0; $x < count($mode); $x++) {
                for ($i = 0; $i < count($latLon); $i++) {
                    $data = Presale::select([$latLon[$i]])
                        ->where("wilayah_id", $wilayah_id)
                        ->where("deleted_at", null)
                        ->orderBy($latLon[$i], ($mode[$x] == 'start' ? 'asc' : 'desc'))->first();

                    if ($latLon[$i] == 'lat') {
                        $point[$mode[$x]]['lat'] = $data->lat;
                    } else {
                        $point[$mode[$x]]['lon'] = $data->lon;
                    }
                }
            }

            $lat = $point["start"]["lat"] + ($point["end"]["lat"] - $point["start"]["lat"]) / 2;
            $lon = $point["start"]["lon"] + ($point["end"]["lon"] - $point["start"]["lon"]) / 2;

            $return = ["lat" => $lat, "lon" => $lon];
            return $return;
        }

        return false;
    }

    public function getCompanyWilayah($company_id)
    {
        $company = Company::find($company_id);
        if ($company->type == 'osp') {
            $data = Wilayah::where('wilayah.company_id',$company_id)
                    ->select(['wilayah.*','companies.name as nama_osp'])
                    ->join('companies','companies.company_id','=','wilayah.company_id')->get();
        }else{
            $data = DB::table('request_wilayah')->select(['wilayah.*','companies.name as nama_osp'])
                    ->join('wilayah','wilayah.wilayah_id','=','request_wilayah.wilayah_id')
                    ->join('companies','companies.company_id','=','wilayah.company_id');
            if ($company->type == 'isp') {
                $data = $data->where('isp_id',$company_id)
                            ->where('status','accepted');   
            }
            $data = $data->groupBy('wilayah.wilayah_id')->orderBy('companies.company_id','asc')->orderBy('wilayah.name')->get();
        }

        return response()->json($data);
    }

    public function request_presale(Wilayah $wilayah) {
        $wilayah->status_presale = 'request';
        $wilayah->request_presale();

        $wilayah->save();

        return response()->json([
            'errors' => false,
            'message' => trans('wilayah::wilayahs.messages.request presale successfull')
        ]);

        
    }
}