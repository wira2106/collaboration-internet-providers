<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class DiskonPaketTransformer extends Resource
{
    public function toArray($request)
    {
       $afterDiskon = $this->harga_paket - (($this->harga_paket * $this->diskon)/100);
       return [
        'harga_paket' => $this->harga_paket,
        'range' => $this->start_pelanggan." - ".$this->end_pelanggan,
        'percentage' => $this->diskon."%",
        'afterPercen' => "Rp. ".number_format($afterDiskon,0,',','.'),
        'diskon' => $this->diskon,
        'start_pelanggan' => $this->start_pelanggan,
        'end_pelanggan' => $this->end_pelanggan,
        'biaya_id' => $this->biaya_id
       ];
    }
}
