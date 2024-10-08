<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\User\Permissions\PermissionManager;

class FullUserTransformer extends Resource
{
    public function toArray($request)
    {
        $permissionsManager = app(PermissionManager::class);
        $permissions = $this->buildPermissionList($permissionsManager->all());

        $data = [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'activated' => $this->isActivated(),
            'last_login' => $this->last_login,
            'profile' => $this->profile,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'permissions' => $permissions,
            'roles' => $this->roles->pluck('id'),
            'urls' => [
                'delete_url' => route('api.user.user.destroy', $this->id),
            ],
        ];

        return $data;
    }

    private function buildPermissionList(array $permissionsConfig) : array
    {
        $list = [];

        if ($permissionsConfig === null) {
            return $list;
        }

        foreach ($permissionsConfig as $mainKey => $subPermissions) {
            foreach ($subPermissions as $key => $permissionGroup) {
                foreach ($permissionGroup as $lastKey => $description) {
                    $list[strtolower($key) . '.' . $lastKey] = current_permission_value($this, $key, $lastKey);
                }
            }
        }

        return $list;
    }
}
