<?php

namespace Modules\Peralatan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Peralatan\Entities\Perangkat;
use Modules\Peralatan\Http\Requests\CreatePerangkatRequest;
use Modules\Peralatan\Http\Requests\UpdatePerangkatRequest;
use Modules\Peralatan\Repositories\PerangkatRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class PerangkatController extends AdminBaseController
{
    /**
     * @var PerangkatRepository
     */
    private $perangkat;

    public function __construct(PerangkatRepository $perangkat)
    {
        parent::__construct();

        $this->perangkat = $perangkat;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$perangkats = $this->perangkat->all();
        $data = Perangkat::orderBy('nama_perangkat')->get();
        return view('peralatan::admin.perangkats.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('peralatan::admin.perangkats.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePerangkatRequest $request
     * @return Response
     */
    public function store(CreatePerangkatRequest $request)
    {
        $this->perangkat->create($request->all());

        return redirect()->route('admin.peralatan.perangkat.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('peralatan::perangkats.title.perangkats')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Perangkat $perangkat
     * @return Response
     */
    public function edit(Perangkat $perangkat)
    {
        return view('peralatan::admin.perangkats.edit', compact('perangkat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Perangkat $perangkat
     * @param  UpdatePerangkatRequest $request
     * @return Response
     */
    public function update(Perangkat $perangkat, UpdatePerangkatRequest $request)
    {
        $this->perangkat->update($perangkat, $request->all());

        return redirect()->route('admin.peralatan.perangkat.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('peralatan::perangkats.title.perangkats')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Perangkat $perangkat
     * @return Response
     */
    public function destroy(Perangkat $perangkat)
    {
        $this->perangkat->destroy($perangkat);

        return redirect()->route('admin.peralatan.perangkat.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('peralatan::perangkats.title.perangkats')]));
    }
}
