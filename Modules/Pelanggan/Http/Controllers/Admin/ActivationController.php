<?php

namespace Modules\Pelanggan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Pelanggan\Entities\Activation;
use Modules\Pelanggan\Http\Requests\CreateActivationRequest;
use Modules\Pelanggan\Http\Requests\UpdateActivationRequest;
use Modules\Pelanggan\Repositories\ActivationRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ActivationController extends AdminBaseController
{
    /**
     * @var ActivationRepository
     */
    private $activation;

    public function __construct(ActivationRepository $activation)
    {
        parent::__construct();

        $this->activation = $activation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$activations = $this->activation->all();

        return view('pelanggan::admin.activations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pelanggan::admin.activations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateActivationRequest $request
     * @return Response
     */
    public function store(CreateActivationRequest $request)
    {
        $this->activation->create($request->all());

        return redirect()->route('admin.pelanggan.activation.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('pelanggan::activations.title.activations')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Activation $activation
     * @return Response
     */
    public function edit(Activation $activation)
    {
        return view('pelanggan::admin.activations.edit', compact('activation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Activation $activation
     * @param  UpdateActivationRequest $request
     * @return Response
     */
    public function update(Activation $activation, UpdateActivationRequest $request)
    {
        $this->activation->update($activation, $request->all());

        return redirect()->route('admin.pelanggan.activation.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('pelanggan::activations.title.activations')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Activation $activation
     * @return Response
     */
    public function destroy(Activation $activation)
    {
        $this->activation->destroy($activation);

        return redirect()->route('admin.pelanggan.activation.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('pelanggan::activations.title.activations')]));
    }
}
