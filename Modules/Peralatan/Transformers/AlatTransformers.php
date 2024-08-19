<?php

namespace Modules\Peralatan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class AlatTransformers extends Resource
{
    public function toArray($request)
    {
        return [
            'alat_id' => $this->alat_id,
            'nama_alat' => $this->nama_alat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'urls' => [
                'delete_url' => route('admin.peralatan.alat.destroy', $this->alat_id),
            ],
        ];
    }
}
