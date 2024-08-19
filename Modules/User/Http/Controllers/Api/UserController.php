<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Requests\Request as RequestsRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\Sentinel\User;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Repositories\UserRepository;
use Modules\User\Transformers\FullUserTransformer;
use Modules\User\Transformers\UserTransformer;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var PermissionManager
     */
    private $permissions;


    public function __construct(UserRepository $user, PermissionManager $permissions)
    {
        $this->user = $user;
        $this->permissions = $permissions;
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }



    public function index(Request $request)
    {
        $company = Auth::user()->company();
        if (Auth::user()->hasAllAccessCompanies()) $company = Company::find($request->get('company_id', $company->company_id));

        return UserTransformer::collection($this->user->serverPaginationFilteringFor($company, $request))->additional(['companies' => Auth::user()->allCompanies()]);
    }

    public function find(User $user)
    {
        return new FullUserTransformer($user->load('roles'));
    }

    public function findNew(User $user)
    {
        return (new FullUserTransformer($user))->additional(['data' => ['is_new' => true]]);
    }

    public function store(CreateUserRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->user->createWithRoles($data, $request->get('roles'), $request->get('is_activated'));
        $this->log->createLog(
            trans('user::users.logs.edit.tipe'), 
            trans('user::users.logs.create.description', [
                'data' => $request->full_name
            ])
        );

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.user created'),
        ]);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        // dd($request->all());
        $data = $this->mergeRequestWithPermissions($request);
        $this->user->updateAndSyncRoles($user->id, $data, $request->get('roles'));
        $this->log->createLog(
            trans('user::users.logs.edit.tipe'), 
            trans('user::users.logs.edit.description', [
                'data' => $request->full_name
            ])
        );

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.user updated'),
        ]);
    }

    public function profile(RequestsRequest $request)
    {
        dd($request);
    }

    public function destroy(User $user)
    {
        $this->user->delete($user->id);
        $this->log->createLog(
            trans('user::logs.tipe.delete'), 
            trans('user::users.log.delete', [
                'data' => $user->full_name
            ])
        );

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.user deleted'),
        ]);
    }

    public function sendResetPassword(User $user, Authentication $auth)
    {
        $code = $auth->createReminderCode($user);

        event(new UserHasBegunResetProcess($user, $code));

        return response()->json([
            'errors' => false,
            'message' => trans('user::auth.reset password email was sent'),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function mergeRequestWithPermissions(Request $request): array
    {
        $permissions = $this->permissions->clean($request->get('permissions'));
        return array_merge($request->all(), ['permissions' => $permissions]);
    }

    public function getRolesPermission(Request $req)
    {
        if ($req->user != null) {
            $user   = User::find($req->user);
            $roles  = $user->getRoles()->first();
        } else {
            $user   = Auth::User();
            $roles  = $user->getRoles()->first();
        }
        $roles->company = $user->company();

        return response()->json($roles);
    }

    public function findByEmail(Request $request) {
        return response()->json([
            'errors' => false,
            'valid' => !Auth::user()->isRegistered($request)
        ]);
    }
}
