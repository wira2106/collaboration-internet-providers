<?php

namespace Modules\Peralatan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Peralatan\Entities\Peralatan;
use Modules\Peralatan\Http\Requests\CreatePeralatanRequest;
use Modules\Peralatan\Http\Requests\UpdatePeralatanRequest;
use Modules\Peralatan\Repositories\PeralatanRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class PeralatanController extends AdminBaseController
{
    /**
     * @var PeralatanRepository
     */
    private $peralatan;

    public function __construct(PeralatanRepository $peralatan)
    {
        parent::__construct();

        $this->peralatan = $peralatan;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$peralatans = $this->peralatan->all();

        return view('peralatan::admin.peralatans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('peralatan::admin.peralatans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePeralatanRequest $request
     * @return Response
     */
    public function store(CreatePeralatanRequest $request)
    {
        $this->peralatan->create($request->all());

        return redirect()->route('admin.peralatan.peralatan.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('peralatan::peralatans.title.peralatans')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Peralatan $peralatan
     * @return Response
     */
    public function edit(Peralatan $peralatan)
    {
        return view('peralatan::admin.peralatans.edit', compact('peralatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Peralatan $peralatan
     * @param  UpdatePeralatanRequest $request
     * @return Response
     */
    public function update(Peralatan $peralatan, UpdatePeralatanRequest $request)
    {
        $this->peralatan->update($peralatan, $request->all());

        return redirect()->route('admin.peralatan.peralatan.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('peralatan::peralatans.title.peralatans')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Peralatan $peralatan
     * @return Response
     */
    public function destroy(Peralatan $peralatan)
    {
        $this->peralatan->destroy($peralatan);

        return redirect()->route('admin.peralatan.peralatan.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('peralatan::peralatans.title.peralatans')]));
    }
}
