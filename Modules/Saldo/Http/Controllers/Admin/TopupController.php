<?php

namespace Modules\Saldo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Saldo\Entities\topup;
use Modules\Saldo\Http\Requests\CreatetopupRequest;
use Modules\Saldo\Http\Requests\UpdatetopupRequest;
use Modules\Saldo\Repositories\topupRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class topupController extends AdminBaseController
{
    /**
     * @var topupRepository
     */
    private $topup;

    public function __construct(topupRepository $topup)
    {
        parent::__construct();

        $this->topup = $topup;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$topups = $this->topup->all();

        return view('saldo::admin.topups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('saldo::admin.topups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatetopupRequest $request
     * @return Response
     */
    public function store(CreatetopupRequest $request)
    {
        $this->topup->create($request->all());

        return redirect()->route('admin.saldo.topup.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('saldo::topups.title.topups')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  topup $topup
     * @return Response
     */
    public function edit(topup $topup)
    {
        return view('saldo::admin.topups.edit', compact('topup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  topup $topup
     * @param  UpdatetopupRequest $request
     * @return Response
     */
    public function update(topup $topup, UpdatetopupRequest $request)
    {
        $this->topup->update($topup, $request->all());

        return redirect()->route('admin.saldo.topup.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('saldo::topups.title.topups')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  topup $topup
     * @return Response
     */
    public function destroy(topup $topup)
    {
        $this->topup->destroy($topup);

        return redirect()->route('admin.saldo.topup.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('saldo::topups.title.topups')]));
    }
}
