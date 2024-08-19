<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DetailAktivasiTransfomer extends Resource
{
    public function toArray($request)
    {        
        return [
            'aktivasi_id' => $this->aktivasi_id,
            'keterangan' => $this->keterangan,
            'osp_id' => $this->osp_id,
            'pelanggan_id' => $this->pelanggan_id,
            'status' => $this->status,
            'status_step'=>$this->status_step,
            'tgl_aktivasi' => $this->tgl_aktivasi,
            'noc' => $this->noc,
            'keterangan_unapprove' => $this->keterangan_unapprove
        ];
    }
}
