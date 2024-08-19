<?php

namespace Modules\Presale\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ListEndpointTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'nama_end_point' => $this->nama_end_point,
            'address' => $this->address, 
            'end_point_id' => $this->end_point_id
        ];
    }
}
