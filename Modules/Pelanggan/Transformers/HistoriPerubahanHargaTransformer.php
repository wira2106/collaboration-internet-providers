<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class HistoriPerubahanHargaTransformer extends Resource
{
    public function toArray($request)
    {        
        return [
            'perubahan_harga_id'=> $this->perubahan_harga_id,
            'pelanggan_id'=> $this->pelanggan_id,
            'harga_otc'=> "Rp " . number_format($this->harga_otc,0,',','.'),
            'harga_otc_nonFormat'=> $this->harga_otc,
            'harga_mrc'=> "Rp " . number_format($this->harga_mrc,0,',','.'),
            'harga_mrc_nonFormat'=> $this->harga_mrc,
            'nama_paket'=>$this->nama_paket,
            'status'=> $this->status,
            'keterangan'=> $this->keterangan,
            'created_by'=>$this->created_by,
            'created_at'=> Date('d-m-Y',strtotime($this->created_at)),
        ];
    }
}