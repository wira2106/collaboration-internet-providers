<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class RangeBiayaKabelTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'range_id' => $this->range_id,
            'panjang_kabel' => $this->panjang_kabel,
            'biaya_kabel_id' => $this->biaya_kabel_id,
            'biaya' => $this->biaya,
            'urls' => [
                'delete_url' => Auth::user()->hasAccess('company.biaya_kabel.destroy') ? route('api.company.biayakabel.range.delete', $this->range_id) : null,
            ],
        ];
    }
}