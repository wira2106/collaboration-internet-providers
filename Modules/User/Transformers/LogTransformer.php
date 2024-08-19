<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class LogTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'tipe' => $this->tipe,
            'deskripsi' => $this->deskripsi,
            'nama_perusahaan' => $this->nama_perusahaan,
            'nama_user' => $this->nama_user,
            'foto' => url('/uploadgambar/' . $this->foto),
            'tanggal' => date('Y-m-d', strtotime($this->created_at)),
            'waktu' => date('H:i', strtotime($this->created_at)),
        ];
    }
}
