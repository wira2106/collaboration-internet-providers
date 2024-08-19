<?php

namespace Modules\Peralatan\Http\Controllers\Api;

use App\Http\Requests\Request as RequestsRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Modules\Peralatan\Entities\Barang;
use Modules\Peralatan\Http\Requests\CreateBarangRequest;
use Modules\Peralatan\Http\Requests\UpdateBarangRequest;
use Modules\Peralatan\Repositories\BarangRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Peralatan\Entities\BarangTranslation;
use Modules\Peralatan\Transformers\BarangTransformers;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    private $barang;

    public function __construct(BarangRepository $barang)
    {
        $this->barang = $barang;
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        return BarangTransformers::collection($this->serverPaginationFilteringFor($request));
    }
    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $roles = new Barang();
        $roles =  DB::table('barang')->whereNull('deleted_at');
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $roles->where('nama_barang', 'LIKE', "%{$term}%")
                ->orWhere('barang_id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles->orderBy('nama_barang', 'asc');
        }

        return $roles->paginate($request->get('per_page', 10));
    }

    public function store(Request $request)
    {
        // foreach ($request->nama_alat as $key => $value) {
        //     $this->alat->create([
        //         'nama_alat' => $value['value'],
        //         'created_at' => date('Y-m-d H:i:s')
        //     ]);
        // }
        $this->log->createLog(trans('user::logs.tipe.create'), trans('peralatan::barangs.log.create'));
        foreach ($request->barang as $key => $value) {
            $this->barang->create([
                'nama_barang' => $value['nama_barang'],
                'tipe_qty' => $value['tipe_qty'],
                'harga_per_pcs' => $value['harga_per_pcs'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::barangs.messages.barang save'),
        ]);
    }

    public function find(Barang $barang)
    {
        return Barang::where('barang_id', $barang->barang_id)->first();
    }

    public function update(Barang $barang, UpdateBarangRequest $request)
    {
        $this->log->createLog(trans('user::logs.tipe.update'), trans('peralatan::barangs.log.update'));
        Barang::where('barang_id',  $barang->barang_id)
            ->update([
                'nama_barang' => $request->barang[0]['nama_barang'],
                'tipe_qty' => $request->barang[0]['tipe_qty'],
                'harga_per_pcs' => $request->barang[0]['harga_per_pcs'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::barangs.messages.barang save'),
        ]);
    }

    public function destroy(Barang $barang)
    {
        $this->log->createLog(trans('user::logs.tipe.delete'), trans('peralatan::barangs.log.delete'));
        $barang = $this->barang->find($barang->barang_id);
        $barang->delete();
        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::barangs.messages.barang deleted')
        ]);
    }
}
