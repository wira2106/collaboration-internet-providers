<?php

namespace Modules\Company\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Entities\Staff;
use Modules\Company\Http\Requests\CreateStaffRequest;
use Modules\Company\Http\Requests\UpdateStaffRequest;
use Modules\Company\Repositories\StaffRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class StaffController extends AdminBaseController
{
    /**
     * @var StaffRepository
     */
    private $staff;

    public function __construct(StaffRepository $staff)
    {
        parent::__construct();

        $this->staff = $staff;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$staff = $this->staff->all();

        return view('company::admin.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('company::admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateStaffRequest $request
     * @return Response
     */
    public function store(CreateStaffRequest $request)
    {
        $this->staff->create($request->all());

        return redirect()->route('admin.company.staff.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('company::staff.title.staff')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Staff $staff
     * @return Response
     */
    public function edit(Staff $staff)
    {
        return view('company::admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Staff $staff
     * @param  UpdateStaffRequest $request
     * @return Response
     */
    public function update(Staff $staff, UpdateStaffRequest $request)
    {
        $this->staff->update($staff, $request->all());

        return redirect()->route('admin.company.staff.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('company::staff.title.staff')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Staff $staff
     * @return Response
     */
    public function destroy(Staff $staff)
    {
        $this->staff->destroy($staff);

        return redirect()->route('admin.company.staff.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('company::staff.title.staff')]));
    }
}
