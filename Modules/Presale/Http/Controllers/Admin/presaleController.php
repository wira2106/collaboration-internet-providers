<?php

namespace Modules\Presale\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Presale\Entities\presale;
use Modules\Presale\Http\Requests\CreatepresaleRequest;
use Modules\Presale\Http\Requests\UpdatepresaleRequest;
use Modules\Presale\Repositories\presaleRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class presaleController extends AdminBaseController
{
    /**
     * @var presaleRepository
     */
    private $presale;

    public function __construct(presaleRepository $presale)
    {
        parent::__construct();

        $this->presale = $presale;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$presales = $this->presale->all();
        return view('presale::admin.presales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('presale::admin.presales.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatepresaleRequest $request
     * @return Response
     */
    public function store(CreatepresaleRequest $request)
    {
        $this->presale->create($request->all());

        return redirect()->route('admin.presale.presale.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('presale::presales.title.presales')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  presale $presale
     * @return Response
     */
    public function edit(presale $presale)
    {
        return view('presale::admin.presales.edit', compact('presale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  presale $presale
     * @param  UpdatepresaleRequest $request
     * @return Response
     */
    public function update(presale $presale, UpdatepresaleRequest $request)
    {
        $this->presale->update($presale, $request->all());

        return redirect()->route('admin.presale.presale.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('presale::presales.title.presales')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  presale $presale
     * @return Response
     */
    public function destroy(presale $presale)
    {
        $this->presale->destroy($presale);

        return redirect()->route('admin.presale.presale.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('presale::presales.title.presales')]));
    }
}
