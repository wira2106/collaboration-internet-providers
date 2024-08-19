<?php

namespace Modules\Wilayah\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
class ListPaketPengajuanTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'paket_id' => $this->paket_id,
            'nama_paket' => $this->nama_paket,
            'biaya_otc' => $this->biaya_otc,
            'harga_paket' => $this->harga_paket,
            'sla' => $this->sla,
            'biaya_otc_rp' => 'Rp. '.number_format($this->biaya_otc,0,',','.'),
            'harga_paket_rp' => 'Rp. '.number_format($this->harga_paket,0,',','.'),
            'sla_percent' => $this->sla."%",
            'urls' => [
                'delete_url' =>  route('api.wilayah.pengajuan.paket.destroy',[$this->paket_id,$this->request_wilayah_id]),
            ],
        ];
    }
}