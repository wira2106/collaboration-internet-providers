<?php

namespace Modules\Pelanggan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Pelanggan\Entities\Installation;
use Modules\Pelanggan\Http\Requests\CreateInstallationRequest;
use Modules\Pelanggan\Http\Requests\UpdateInstallationRequest;
use Modules\Pelanggan\Repositories\InstallationRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class InstallationController extends AdminBaseController
{
    /**
     * @var InstallationRepository
     */
    private $installation;

    public function __construct(InstallationRepository $installation)
    {
        parent::__construct();

        $this->installation = $installation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$installations = $this->installation->all();

        return view('pelanggan::admin.installations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pelanggan::admin.installations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateInstallationRequest $request
     * @return Response
     */
    public function store(CreateInstallationRequest $request)
    {
        $this->installation->create($request->all());

        return redirect()->route('admin.pelanggan.installation.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('pelanggan::installations.title.installations')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Installation $installation
     * @return Response
     */
    public function edit(Installation $installation)
    {
        return view('pelanggan::admin.installations.edit', compact('installation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Installation $installation
     * @param  UpdateInstallationRequest $request
     * @return Response
     */
    public function update(Installation $installation, UpdateInstallationRequest $request)
    {
        $this->installation->update($installation, $request->all());

        return redirect()->route('admin.pelanggan.installation.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('pelanggan::installations.title.installations')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Installation $installation
     * @return Response
     */
    public function destroy(Installation $installation)
    {
        $this->installation->destroy($installation);

        return redirect()->route('admin.pelanggan.installation.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('pelanggan::installations.title.installations')]));
    }
}
