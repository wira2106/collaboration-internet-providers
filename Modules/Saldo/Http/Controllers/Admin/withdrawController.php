<?php

namespace Modules\Saldo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Saldo\Entities\withdraw;
use Modules\Saldo\Http\Requests\CreatewithdrawRequest;
use Modules\Saldo\Http\Requests\UpdatewithdrawRequest;
use Modules\Saldo\Repositories\withdrawRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class withdrawController extends AdminBaseController
{
    /**
     * @var withdrawRepository
     */
    private $withdraw;

    public function __construct(withdrawRepository $withdraw)
    {
        parent::__construct();

        $this->withdraw = $withdraw;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$withdraws = $this->withdraw->all();

        return view('saldo::admin.withdraws.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('saldo::admin.withdraws.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatewithdrawRequest $request
     * @return Response
     */
    public function store(CreatewithdrawRequest $request)
    {
        $this->withdraw->create($request->all());

        return redirect()->route('admin.saldo.withdraw.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('saldo::withdraws.title.withdraws')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  withdraw $withdraw
     * @return Response
     */
    public function edit(withdraw $withdraw)
    {
        return view('saldo::admin.withdraws.edit', compact('withdraw'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  withdraw $withdraw
     * @param  UpdatewithdrawRequest $request
     * @return Response
     */
    public function update(withdraw $withdraw, UpdatewithdrawRequest $request)
    {
        $this->withdraw->update($withdraw, $request->all());

        return redirect()->route('admin.saldo.withdraw.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('saldo::withdraws.title.withdraws')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  withdraw $withdraw
     * @return Response
     */
    public function destroy(withdraw $withdraw)
    {
        $this->withdraw->destroy($withdraw);

        return redirect()->route('admin.saldo.withdraw.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('saldo::withdraws.title.withdraws')]));
    }
}
