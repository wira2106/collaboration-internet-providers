<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class DiscountPaketBerlanggananTransformer extends Resource
{
    public function toArray($request)
    {
        $harga = $this->harga_paket - ($this->harga_paket * $this->diskon / 100);
        return [
            'biaya_id' => $this->biaya_id,
            'paket_id' => $this->paket_id,
            'nama_paket' => $this->nama_paket,
            'diskon' => $this->diskon,
            'start_pelanggan' => $this->start_pelanggan,
            'end_pelanggan' => $this->end_pelanggan,
            'harga_paket' => number_format($harga),
            'urls' => [
                'delete_url' => route('api.company.diskonpaketberlangganan.deleted', $this->biaya_id)
            ],
        ];
    }
}
