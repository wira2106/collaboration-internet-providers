<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ListPelangganSelect extends Resource
{
    public function toArray($request)
    {        
        return [
            'pelanggan_id' => $this->pelanggan_id,
            'nama_pelanggan' => $this->nama_pelanggan,
            'biaya_mrc' => $this->biaya_mrc,
            'nama_paket' => $this->nama_paket,
            'nama_pelanggan2' => $this->nama_pelanggan." (".$this->nama_paket.")",
            'ticket_id'=>(isset($this->ticket_id)?$this->ticket_id:null)
        ];
    }
}