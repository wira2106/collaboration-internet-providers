<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class UserTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'full_name' => $this->present()->full_name,
            'email' => $this->email,
            'created_at' => $this->created_at,

            'urls' => [
                'delete_url' => route('api.user.user.destroy', $this->id),
            ],
        ];
    }
}
