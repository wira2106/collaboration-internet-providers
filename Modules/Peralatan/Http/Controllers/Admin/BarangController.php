<?php

namespace Modules\Peralatan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Peralatan\Entities\Barang;
use Modules\Peralatan\Http\Requests\CreateBarangRequest;
use Modules\Peralatan\Http\Requests\UpdateBarangRequest;
use Modules\Peralatan\Repositories\BarangRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class BarangController extends AdminBaseController
{
    /**
     * @var BarangRepository
     */
    private $barang;

    public function __construct(BarangRepository $barang)
    {
        parent::__construct();

        $this->barang = $barang;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$barangs = $this->barang->all();
        $data = Barang::all();
        return view('peralatan::admin.barangs.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('peralatan::admin.barangs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateBarangRequest $request
     * @return Response
     */
    public function store(CreateBarangRequest $request)
    {
        $this->barang->create($request->all());

        return redirect()->route('admin.peralatan.barang.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('peralatan::barangs.title.barangs')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Barang $barang
     * @return Response
     */
    public function edit(Barang $barang)
    {
        return view('peralatan::admin.barangs.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Barang $barang
     * @param  UpdateBarangRequest $request
     * @return Response
     */
    public function update(Barang $barang, UpdateBarangRequest $request)
    {
        $this->barang->update($barang, $request->all());

        return redirect()->route('admin.peralatan.barang.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('peralatan::barangs.title.barangs')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Barang $barang
     * @return Response
     */
    public function destroy(Barang $barang)
    {
        $this->barang->destroy($barang);

        return redirect()->route('admin.peralatan.barang.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('peralatan::barangs.title.barangs')]));
    }
}
