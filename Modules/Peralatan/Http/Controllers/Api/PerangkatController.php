<?php

namespace Modules\Peralatan\Http\Controllers\Api;

use App\Http\Requests\Request as RequestsRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Modules\Peralatan\Entities\Perangkat;
use Modules\Peralatan\Http\Requests\CreatePerangkatRequest;
use Modules\Peralatan\Http\Requests\UpdatePerangkatRequest;
use Modules\Peralatan\Repositories\PerangkatRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Peralatan\Entities\PerangkatTranslation;
use Modules\Peralatan\Transformers\PerangkatTransformers;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PerangkatController extends Controller
{
    private $perangkat;

    public function __construct(PerangkatRepository $perangkat)
    {
        $this->perangkat = $perangkat;
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        return PerangkatTransformers::collection($this->serverPaginationFilteringFor($request));
    }
    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $roles = new Perangkat();
        $roles =  DB::table('perangkat')->whereNull('deleted_at');
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $roles->where('nama_perangkat', 'LIKE', "%{$term}%")
                ->orWhere('perangkat_id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles->orderBy('nama_perangkat', 'asc');
        }

        return $roles->paginate($request->get('per_page', 10));
    }

    public function store(CreatePerangkatRequest $request)
    {
        $this->log->createLog(trans('user::logs.tipe.create'), trans('peralatan::perangkats.log.create'));
        if(is_array($request->nama_perangkat)){
            foreach ($request->nama_perangkat as $key => $value) {
                $this->perangkat->create([
                    'nama_perangkat' => $value['value'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            return response()->json([
                'errors' => false,
                'message' => trans('peralatan::perangkats.messages.perangkat save'),
            ]);
        }else{
            $data = Perangkat::insertGetId([
                'nama_perangkat' => $request->nama_perangkat,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return response()->json([
                'errors' => false,
                'id' => $data,
                'message' => trans('peralatan::perangkats.messages.perangkat save'),
            ]);
        }
    }

    public function find(Perangkat $perangkat)
    {
        return Perangkat::where('perangkat_id', $perangkat->perangkat_id)->first();
    }

    public function update(Perangkat $perangkat, UpdatePerangkatRequest $request)
    {
        $this->log->createLog(trans('user::logs.tipe.update'), trans('peralatan::perangkats.log.update'));
        Perangkat::where('perangkat_id',  $perangkat->perangkat_id)
            ->update([
                'nama_perangkat' => $request->nama_perangkat[0]['value'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::perangkats.messages.perangkat save'),
        ]);
    }

    public function destroy(Perangkat $perangkat)
    {
        $this->log->createLog(trans('user::logs.tipe.delete'), trans('peralatan::perangkats.log.delete'));
        $perangkat = $this->perangkat->find($perangkat->perangkat_id);
        $perangkat->delete();
        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::perangkats.messages.perangkat deleted')
        ]);
    }
}
