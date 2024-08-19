<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DetailStaffTransformer extends Resource
{
    public function toArray($request)
    {

        return [
            'company_id' => $this->company_id,
			'deleted_at' => $this->deleted_at,
			'email' => $this->email,
			'full_name' => $this->full_name,
			'user_id' => $this->id,
			'last_login' => $this->last_login,			
			'permissions' => $this->permissions,
			'phone' => $this->phone,
			'photo_profile' => $this->photo_profile,
			'user_picture' => url('/uploadgambar/'.$this->photo_profile),
			'role' => $this->getRoles()->first()->slug
        ];
    }
}
