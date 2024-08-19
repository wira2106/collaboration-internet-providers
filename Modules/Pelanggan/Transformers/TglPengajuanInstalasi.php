<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TglPengajuanInstalasi extends Resource
{
    public function toArray($request)
    {               
        return [
            'id' => $this->id,
		    'pengajuan_id' => $this->pengajuan_id,
		    'status' => $this->status,
		    'tgl_instalasi' => $this->tgl_instalasi,
		    'slot_id' => $this->slot_id,
            'tanggal'=>date('d F Y', strtotime($this->tgl_instalasi)),
		    'full_name' => $this->name." (".date('H:i', strtotime($this->jam_start))." - ".date('H:i', strtotime($this->jam_end)).")",
        ];
    }
}