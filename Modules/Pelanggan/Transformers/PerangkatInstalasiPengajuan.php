<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class PerangkatInstalasiPengajuan extends Resource
{
    public function toArray($request)
    {
        return [
            'label'=>$this->nama_perangkat,
            'value'=>$this->perangkat_id,
        ];
    }
}