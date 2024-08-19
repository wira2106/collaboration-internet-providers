<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class PaketBerlanggananTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'paket_id' => $this->paket_id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'nama_paket' => $this->nama_paket,
            'biaya_otc' => number_format($this->biaya_otc),
            'harga_paket' => number_format($this->harga_paket),
            'harga_paket_default' => $this->harga_paket,
            'sla' => $this->sla,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'urls' => [
                'delete_url' => route('api.company.paketberlangganan.delete', $this->paket_id)
            ],


        ];
    }
}
