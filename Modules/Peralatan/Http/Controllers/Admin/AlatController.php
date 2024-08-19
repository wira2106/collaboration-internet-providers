<?php

namespace Modules\Peralatan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Peralatan\Entities\Alat;
use Modules\Peralatan\Http\Requests\CreateAlatRequest;
use Modules\Peralatan\Http\Requests\UpdateAlatRequest;
use Modules\Peralatan\Repositories\AlatRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Peralatan\Entities\AlatTranslation;
use Modules\Peralatan\Transformers\AlatTransformers;
use DB;


class AlatController extends AdminBaseController
{
    /**
     * @var AlatRepository
     */
    private $alat;

    public function __construct(AlatRepository $alat)
    {
        parent::__construct();

        $this->alat = $alat;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$alats = $this->alat->all();
        return view('peralatan::admin.alats.index');
    }

    public function view(Request $request)
    {
        // $cari = $request->cari;
        // $data = DB::table('alat')
        //         ->select('alat.*')
        //         ->whereNull('deleted_at');
        // if ($cari != null) {
        //     $data = $data
        //             ->where(function($query) use($cari){
        //                 $query->where('nama_alat','LIKE','%'.$cari.'%');
        //             });
        // }

        // $send = [
        //     $jumlah= $data->count(),
        //     $data = $data->skip($request->page*10)->take(10)->get()
        // ];
        // return json_encode($send);
        return Alat::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('peralatan::admin.alats.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateAlatRequest $request
     * @return Response
     */
    public function store(CreateAlatRequest $request)
    {
        foreach ($request->nama_alat as $key => $value) {
            $this->alat->create([
                'nama_alat' => $value['value'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->route('admin.peralatan.alat.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('peralatan::alats.title.alats')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Alat $alat
     * @return Response
     */
    public function edit()
    {
        return view('peralatan::admin.alats.edit');
    }

    public function find(Alat $alat)
    {
        return Alat::where('alat_id', $alat->alat_id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Alat $alat
     * @param  UpdateAlatRequest $request
     * @return Response
     */
    public function update(Alat $alat, UpdateAlatRequest $request)
    {
        $data = DB::table('alat')
            ->where('alat_id', '=', $alat->alat_id)
            ->update([
                'nama_alat' => $request->nama_alat[0]['value'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        return json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Alat $alat
     * @return Response
     */
    public function destroy(Alat $alat)
    {
        $alat = $this->alat->find($alat->alat_id);
        $alat->delete();
        return response()->json([
            'errors' => false,
            'message' => trans('peralatan::alats.messages.alat deleted')
        ]);
    }
}
