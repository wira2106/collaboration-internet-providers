<?php

namespace Modules\Pelanggan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Pelanggan\Entities\SalesOrder;
use Modules\Pelanggan\Http\Requests\CreateSalesOrderRequest;
use Modules\Pelanggan\Http\Requests\UpdateSalesOrderRequest;
use Modules\Pelanggan\Repositories\SalesOrderRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class SalesOrderController extends AdminBaseController
{
    /**
     * @var SalesOrderRepository
     */
    private $salesorder;

    public function __construct(SalesOrderRepository $salesorder)
    {
        parent::__construct();

        $this->salesorder = $salesorder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$salesorders = $this->salesorder->all();

        return view('pelanggan::admin.salesorders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pelanggan::admin.salesorders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateSalesOrderRequest $request
     * @return Response
     */
    public function store(CreateSalesOrderRequest $request)
    {
        $this->salesorder->create($request->all());

        return redirect()->route('admin.pelanggan.salesorder.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('pelanggan::salesorders.title.salesorders')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  SalesOrder $salesorder
     * @return Response
     */
    public function edit(SalesOrder $salesorder)
    {
        return view('pelanggan::admin.salesorders.edit', compact('salesorder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SalesOrder $salesorder
     * @param  UpdateSalesOrderRequest $request
     * @return Response
     */
    public function update(SalesOrder $salesorder, UpdateSalesOrderRequest $request)
    {
        $this->salesorder->update($salesorder, $request->all());

        return redirect()->route('admin.pelanggan.salesorder.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('pelanggan::salesorders.title.salesorders')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SalesOrder $salesorder
     * @return Response
     */
    public function destroy(SalesOrder $salesorder)
    {
        $this->salesorder->destroy($salesorder);

        return redirect()->route('admin.pelanggan.salesorder.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('pelanggan::salesorders.title.salesorders')]));
    }
}
