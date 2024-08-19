<?php

namespace Modules\User\Http\Controllers\Api;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\CreateRoleRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Transformers\FullRoleTransformer;
use Modules\User\Transformers\RoleTransformer;
use DB;

class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var PermissionManager
     */
    private $permissions;

    public function __construct(RoleRepository $role, PermissionManager $permissions)
    {
        $this->role = $role;
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
        $this->permissions = $permissions;
    }

    public function all()
    {
        return RoleTransformer::collection($this->role->all());
    }

    public function index(Request $request)
    {
        return RoleTransformer::collection($this->role->serverPaginationFilteringFor($request));
    }

    public function find(EloquentRole $role)
    {
        return new FullRoleTransformer($role->load('users'));
    }

    public function findNew(EloquentRole $role)
    {
        return new FullRoleTransformer($role);
    }

    public function store(CreateRoleRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->role->create($data);
        $this->log->createLog(trans('user::logs.tipe.create'), trans('user::users.log.create role', ['data' => $request->name]));

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.role created'),
        ]);
    }

    public function update(EloquentRole $role, UpdateRoleRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->role->update($role->id, $data);
        $this->log->createLog(trans('user::logs.tipe.update'), trans('user::users.log.update role', ['data' => $data['name']]));
        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.role updated'),
        ]);
    }

    public function destroy(EloquentRole $role)
    {

        $data = DB::table('roles')->whereId($role->id)->first();
        $this->log->createLog(trans('user::logs.tipe.delete'), trans('user::users.log.delete role', ['data' => $data->name]));
        $this->role->delete($role->id);
        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.role deleted'),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function mergeRequestWithPermissions(Request $request)
    {
        $permissions = $this->permissions->clean($request->get('permissions'));

        return array_merge($request->all(), ['permissions' => $permissions]);
    }
}
