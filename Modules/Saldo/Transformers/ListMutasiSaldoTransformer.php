<?php

namespace Modules\Saldo\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ListMutasiSaldoTransformer extends Resource
{
    public function toArray($request)
    {        
        return [
          "mutasi_id" => $this->mutasi_id,
          "saldo_id" => $this->saldo_id,
          "nominal" => $this->nominal,
          "nominal_rp" => "Rp. ".number_format($this->nominal,0,',','.'),
          "tipe" => $this->tipe,
          "created_at" => date('d M Y H:i:s', strtotime($this->created_at)),
          "tanggal" => date('d M Y', strtotime($this->created_at)),
          "jam" => date('H:i:s', strtotime($this->created_at)),
          "deskripsi" => $this->deskripsi
        ];
    }
}
