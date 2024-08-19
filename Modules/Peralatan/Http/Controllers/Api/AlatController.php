<?php

namespace Modules\Peralatan\Http\Controllers\Api;

use App\Http\Requests\Request as RequestsRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Modules\Peralatan\Entities\Alat;
use Modules\Peralatan\Http\Requests\CreateAlatRequest;
use Modules\Peralatan\Http\Requests\UpdateAlatRequest;
use Modules\Peralatan\Repositories\AlatRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Peralatan\Entities\AlatTranslation;
use Modules\Peralatan\Transformers\AlatTransformers;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AlatController extends Controller
{
    /**
     * @var AlatRepository
     */
    private $alat;

    public function __construct(AlatRepository $alat)
    {
        $this->alat = $alat;
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }
    /**
     * index
     */
    public function index(Request $request)
    {
        return AlatTransformers::collection($this->serverPaginationFilteringFor($request));
    }

    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $roles = new Alat();
        $roles =  DB::table('alat')->whereNull('deleted_at');
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $roles->where('nama_alat', 'LIKE', "%{$term}%")
                ->orWhere('alat_id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles->orderBy('nama_alat', 'asc');
        }

        return $roles->paginate($request->get('per_page', 10));
    }

    public function store(CreateAlatRequest $request)
    {
        $this->log->createLog(trans('user::logs.tipe.create'), trans('peralatan::alats.log.create'));
        if(is_array($request->nama_alat)){
            foreach ($request->nama_alat as $key => $value) {
                $this->alat->create([
                    'nama_alat' => $value['value'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            return response()->json([
                'errors' => false,
                'message' => trans('peralatan::alats.messages.alat save'),
            ]);
        }else{
            $data = Alat::insertGetId([
                'nama_alat' => $request->nama_alat,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return response()->json([
                'errors' => false,
                'id' => $data,
                'message' => trans('peralatan::alats.messages.alat save'),
            ]);
        }
    }

    public function find(Alat $alat)
    {
        return Alat::where('alat_id', $alat->alat_id)->first();
    }
    public function update(Alat $alat, UpdateAlatRequest $request)
    {
        $this->log->createLog(trans('user::logs.tipe.update'), trans('peralatan::alats.log.update'));
        Alat::where('alat_id',  $alat->alat_id)
            ->update([
                'nama_alat' => $request->nama_alat[0]['value'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::alats.messages.alat save'),
        ]);
    }
}
