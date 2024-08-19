<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class JadwalInstalasi extends Resource
{
    public function toArray($request)
    {
        
        $tanggal_instalasi = strtotime(Date($this->tgl_instalasi));
        $today = strtotime(Date('Y-m-d'));
        $sisa_hari = ($tanggal_instalasi - $today) / (60*60*24);

        return [
            'tanggal' => date('d F Y',strtotime($this->tgl_instalasi)),
            'slot' => $this->name . " (" . date('H:i', strtotime($this->jam_start)) . " - " . date('H:i', strtotime($this->jam_end)) . ")",
            'start' => $this->jam_start,
            'end' => $this->jam_end,
            'selisih' => $sisa_hari
        ];
    }
}