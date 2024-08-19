<?php

namespace Modules\Invoice\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class InvoicePelangganTransformer extends Resource
{
    public function toArray($request)
    {
        $status = "";
        if($this->status_invoice != 'settlement' || $this->hold_pengembalian == 1){
            $status = 'Pending';
        }else{
            $status = 'Settlement';
        }
        
        return [
            "pelanggan_id" => $this->pelanggan_id,
            "amount" => "Rp. ".number_format($this->amount,0,',','.'),
            "dpp" => "Rp. ".number_format($this->dpp,0,',','.'),
            "total_ppn" => "Rp. ".number_format($this->total_ppn,0,',','.'),
            "ppn" => "Rp. ".number_format($this->ppn,0,',','.'),
            "nama_pelanggan" => $this->nama_pelanggan,
            "status" => $status,
            'paket' => $this->nama_paket,
        ];
    }
}