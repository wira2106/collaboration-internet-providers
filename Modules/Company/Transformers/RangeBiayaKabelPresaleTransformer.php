<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class RangeBiayaKabelPresaleTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'panjang_kabel' => $this->panjang_kabel,
            'biaya' => $this->biaya,
        ];
    }
}