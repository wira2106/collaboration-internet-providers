<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Company;
use Modules\User\Entities\Sentinel\User;
use Modules\User\Entities\Sentinel\Staff;
use Modules\User\Http\Requests\CreateStaffRequest;
use Modules\User\Transformers\ListStaffTransformer;
use Modules\User\Transformers\DetailStaffTransformer;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }
    public function submit(CreateStaffRequest $req)
    {
        $model = new Staff;
        $data = $model->saveFormStaff($req);

        return response()->json([
            'data'=>$data,
            'errors' => false,
            'message' => $req->user_id == null ? trans('user::staff.messages.staff created') : trans('user::staff.messages.staff updated'),
        ]);
    }

    public function find($staff)
    {
        $model = new Staff;
        $data = $model->detailStaff($staff);

        return new DetailStaffTransformer($data);
    }

    public function pagination(Request $request)
    {
        $company_id = $request->get("company_id");
        if ($company_id == null) {
            $company = Auth::user()->company();
        } else {
            $company = Company::where('company_id', $company_id)->first();
        }

        $data = $this->serverPaginationFilteringFor($company, $request);
        $listCompany = new Company;
        $listCompany = $listCompany->getAllCompany();
        $allDataStaff = ['companies' => $listCompany, "selected" => $company->company_id];

        return ListStaffTransformer::collection($data)->additional($allDataStaff);
    }

    public function serverPaginationFilteringFor(Company $company, Request $request)
    {
        $staff      = DB::table("user_companies")->select(["users.*", "roles.name as role_name"])
            ->join("users", "users.id", "=", "user_companies.user_id")
            ->join('role_users', 'role_users.user_id', 'users.id')
            ->join('roles', 'role_users.role_id', 'roles.id')
            ->where("company_id", $company->company_id);

        if($request->get('role')) {
            $staff->where('role_users.role_id', $request->role);
        }

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $staff->where('full_name', 'LIKE', "%{$term}%");
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $staff->orderBy($request->get('order_by'), $order);
        } else {
            $staff->orderBy('full_name', 'asc');
        }

        return $staff->paginate($request->get('per_page', 10));
    }

    public function deleteStaff(Request $req, $staff)
    {
        $data = User::find($staff);
        DB::table("user_companies")->where("user_id", $staff)->delete();
        $data->delete();
        $this->log->createLog(
            trans('user::staff.logs.destroy.tipe'), 
            trans('user::staff.logs.destroy.description', [
                'data' => $data->full_name
            ])
        );
        return response()->json([
            'errors' => false,
            'message' => trans('user::staff.messages.staff deleted'),
        ]);
    }
}