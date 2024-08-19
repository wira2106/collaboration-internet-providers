<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
class ListStaffTransformer extends Resource
{
    public function toArray($request)
    {
        
        return [
            'id_staff' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'role_name' => $this->role_name,
            'urls' => [
                'delete_url' => Auth::user()->hasAccess('user.staff.destroy') ? route('api.user.staff.destroy', $this->id) : null,                
                'edit_url' => Auth::user()->hasAccess('user.staff.edit') ? route('admin.user.staff.edit', $this->id) : null
            ],
        ];
    }
}