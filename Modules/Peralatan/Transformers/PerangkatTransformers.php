<?php

namespace Modules\Peralatan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class perangkatTransformers extends Resource
{
    public function toArray($request)
    {
        return [
            'perangkat_id' => $this->perangkat_id,
            'nama_perangkat' => $this->nama_perangkat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'urls' => [
                'delete_url' => route('api.peralatan.perangkat.destroy', $this->perangkat_id),
            ],
        ];
    }
}
