<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class UserProfileTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo_profile' => $this->photo_profile,
            'user_picture' => url('/uploadgambar/'.$this->photo_profile),
            'created_at' => $this->created_at,
        ];
    }
}
