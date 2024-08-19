<?php

namespace Modules\User\Entities\Sentinel;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Presenter\PresentableTrait;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\UserCompanies;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\User\Entities\UserInterface;
use Modules\User\Entities\UserToken;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Presenters\UserPresenter;
use Modules\Wilayah\Entities\Wilayah;

class User extends EloquentUser implements UserInterface, AuthenticatableContract
{
    use PresentableTrait, Authenticatable, MediaRelation, SoftDeletes;

    protected $fillable = [
        'email',
        'full_name',
        'phone',
        'password',
        'permissions',
        'photo_profile'
    ];

    /**
     * {@inheritDoc}
     */
    protected $loginNames = ['email'];

    protected $presenter = UserPresenter::class;

    public function __construct(array $attributes = [])
    {
        $this->loginNames = config('asgard.user.config.login-columns');
        // $this->fillable = config('asgard.user.config.fillable');
        if (config()->has('asgard.user.config.presenter')) {
            $this->presenter = config('asgard.user.config.presenter', UserPresenter::class);
        }
        if (config()->has('asgard.user.config.dates')) {
            $this->dates = config('asgard.user.config.dates', []);
        }
        if (config()->has('asgard.user.config.casts')) {
            $this->casts = config('asgard.user.config.casts', []);
        }

        parent::__construct($attributes);
    }

    /**
     * @inheritdoc
     */
    public function hasRoleId($roleId)
    {
        return $this->roles()->whereId($roleId)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleSlug($slug)
    {
        return $this->roles()->whereSlug($slug)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleName($name)
    {
        return $this->roles()->whereName($name)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function isActivated()
    {
        if (Activation::completed($this)) {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function api_keys()
    {
        return $this->hasMany(UserToken::class);
    }

    /**
     * @inheritdoc
     */
    public function getFirstApiKey()
    {
        $userToken = $this->api_keys->first();

        if ($userToken === null) {
            return '';
        }

        return $userToken->access_token;
    }

    public function __call($method, $parameters)
    {
        #i: Convert array to dot notation
        $config = implode('.', ['asgard.user.config.relations', $method]);

        #i: Relation method resolver
        if (config()->has($config)) {
            $function = config()->get($config);
            $bound = $function->bindTo($this);

            return $bound();
        }

        #i: No relation found, return the call to parent (Eloquent) to handle it.
        return parent::__call($method, $parameters);
    }

    /**
     * @inheritdoc
     */
    public function hasAccess($permission)
    {
        $permissions = $this->getPermissionsInstance();

        return $permissions->hasAccess($permission);
    }

    public function company()
    {
        return Company::find($this->userCompanies->company_id);
    }

    public function userCompanies()
    {
        return $this->hasOne(UserCompanies::class);
    }

    public function hasAllAccessCompanies()
    {
        return $this->userCompanies->company_id === 1;
    }

    public function getRoleName() 
    {
        $role = DB::table('role_users')
                ->where('user_id', $this->id)
                ->join('roles', 'roles.id', 'role_users.role_id')
                ->first();

        return $role ? $role->name : null;
    }

    public function allCompanies()
    {
        if ($this->hasAllAccessCompanies()) {
            return Company::all();
        }

        return [];
    }


    public function allWilayah()
    {
        if ($this->hasAllAccessCompanies()) {
            return Wilayah::all();
        }

        return [];
    }

    public function permissions()
    {
        $permissionsManager = app(PermissionManager::class);
        $permissions = $this->buildPermissionList($permissionsManager->all());
        return $permissions;
    }

    private function buildPermissionList(array $permissionsConfig): array
    {
        $list = [];

        if ($permissionsConfig === null) {
            return $list;
        }
        
        foreach ($permissionsConfig as $mainKey => $subPermissions) {
            foreach ($subPermissions as $key => $permissionGroup) {
                foreach ($permissionGroup as $lastKey => $description) {
                    $list[strtolower($key) . '.' . $lastKey] = $this->hasAccess("$key.$lastKey");
                }
            }
        }

        return $list;
    }

    public function createLog($tipe, $deskripsi, $company_id = null)
    {
        if($company_id === null) $company_id = $this->company()->company_id;

        $data = [
            'company_id' => $company_id,
            'user_id' => $this->id,
            'tipe' => $tipe,
            'deskripsi' => $deskripsi,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return DB::table('log_activity')->insert($data);
    }

    public function isRegistered(Request $request)
    {
        $user = User::where('email', $request->email);
        if ($request->user_id) $user->where('id', '!=', $request->user_id);

        $user = $user->get()->count();


        return $user ? true : false;
    }
}
