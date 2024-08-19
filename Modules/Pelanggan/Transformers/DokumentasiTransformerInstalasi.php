<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DokumentasiTransformerInstalasi extends Resource
{
    public function toArray($request)
    {        
        return [
            'dokumentasi_id'=>$this->id,
            'instalasi_id'=>$this->instalasi_id,
            'foto_dokumentasi_url'=>'../../../uploadgambar/'.$this->foto_dokumentasi,
            'foto_dokumentasi'=>$this->foto_dokumentasi,
            'keterangan'=>$this->keterangan,
            'fotoUpload'=>null,
        ];
    }
}
