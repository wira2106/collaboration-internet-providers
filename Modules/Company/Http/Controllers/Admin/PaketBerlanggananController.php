<?php

namespace Modules\Company\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\Company\Http\Requests\CreatePaketBerlanggananRequest;
use Modules\Company\Http\Requests\UpdatePaketBerlanggananRequest;
use Modules\Company\Repositories\PaketBerlanggananRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

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
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$paketberlangganans = $this->paketberlangganan->all();

        return view('company::admin.paketberlangganans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('company::admin.paketberlangganans.create');
    }


    public function createIsp()
    {
        return view('company::admin.paketberlangganans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePaketBerlanggananRequest $request
     * @return Response
     */
    public function store(CreatePaketBerlanggananRequest $request)
    {
        $this->paketberlangganan->create($request->all());

        return redirect()->route('admin.company.paketberlangganan.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('company::paketberlangganans.title.paketberlangganans')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PaketBerlangganan $paketberlangganan
     * @return Response
     */
    public function edit(PaketBerlangganan $paketberlangganan)
    {
        return view('company::admin.paketberlangganans.edit', compact('paketberlangganan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PaketBerlangganan $paketberlangganan
     * @param  UpdatePaketBerlanggananRequest $request
     * @return Response
     */
    public function update(PaketBerlangganan $paketberlangganan, UpdatePaketBerlanggananRequest $request)
    {
        $this->paketberlangganan->update($paketberlangganan, $request->all());

        return redirect()->route('admin.company.paketberlangganan.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('company::paketberlangganans.title.paketberlangganans')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PaketBerlangganan $paketberlangganan
     * @return Response
     */
    public function destroy(PaketBerlangganan $paketberlangganan)
    {
        $this->paketberlangganan->destroy($paketberlangganan);

        return redirect()->route('admin.company.paketberlangganan.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('company::paketberlangganans.title.paketberlangganans')]));
    }
}
