<?php

namespace Modules\Presale\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Presale\Entities\endpoint;
use Modules\Presale\Http\Requests\CreateendpointRequest;
use Modules\Presale\Http\Requests\UpdateendpointRequest;
use Modules\Presale\Repositories\endpointRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class endpointController extends AdminBaseController
{
    /**
     * @var endpointRepository
     */
    private $endpoint;

    public function __construct(endpointRepository $endpoint)
    {
        parent::__construct();

        $this->endpoint = $endpoint;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$endpoints = $this->endpoint->all();

        return view('presale::admin.endpoints.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('presale::admin.endpoints.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateendpointRequest $request
     * @return Response
     */
    public function store(CreateendpointRequest $request)
    {
        $this->endpoint->create($request->all());

        return redirect()->route('admin.presale.endpoint.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('presale::endpoints.title.endpoints')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  endpoint $endpoint
     * @return Response
     */
    public function edit(endpoint $endpoint)
    {
        return view('presale::admin.endpoints.edit', compact('endpoint'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  endpoint $endpoint
     * @param  UpdateendpointRequest $request
     * @return Response
     */
    public function update(endpoint $endpoint, UpdateendpointRequest $request)
    {
        $this->endpoint->update($endpoint, $request->all());

        return redirect()->route('admin.presale.endpoint.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('presale::endpoints.title.endpoints')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  endpoint $endpoint
     * @return Response
     */
    public function destroy(endpoint $endpoint)
    {
        $this->endpoint->destroy($endpoint);

        return redirect()->route('admin.presale.endpoint.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('presale::endpoints.title.endpoints')]));
    }
}
