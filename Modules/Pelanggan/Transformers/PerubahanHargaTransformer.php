<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PerubahanHargaTransformer extends Resource
{
    public function toArray($request)
    {        
        return [
            'perubahan_harga_id'=>$this->perubahan_harga_id,
            'nama_pelanggan'=>$this->nama_pelanggan,
            'site_id'=>$this->site_id,
            'telepon'=>$this->telepon,
            'email'=>$this->email,
            'nama_paket'=>$this->nama_paket,
            'cancel'=>$this->cancel,
            'status'=>$this->status,
        ];
    }
}
