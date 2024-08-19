<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class BiayaKabelTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'biaya_kabel_id' => $this->biaya_kabel_id,
            'company_id' => $this->company_id,
            'tipe' => $this->tipe,
            'harga_per_meter' => $this->harga_per_meter,
        ];
    }
}