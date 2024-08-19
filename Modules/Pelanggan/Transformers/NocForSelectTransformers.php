<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class NocForSelectTransformers extends Resource
{
    public function toArray($request)
    {        
        return [
            'user_id' => $this->user_id,
            'full_name' => $this->full_name
        ];
    }
}