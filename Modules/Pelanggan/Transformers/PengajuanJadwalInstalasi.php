<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PengajuanJadwalInstalasi extends Resource
{
    public function toArray($request)
    {   
        return [
            'pengajuan_id' => $this->pengajuan_id,
            'pelanggan_id' => $this->pelanggan_id,
            'keterangan' => $this->keterangan,
            'alasan_reschedule'=>$this->alasan_reschedule,
            'tgl_rekomendasi' => ($this->tgl_rekomendasi != null?date('Y-m-d', strtotime($this->tgl_rekomendasi)):null),
            'jam_rekomendasi' => ($this->tgl_rekomendasi != null?date('H:i:s', strtotime($this->tgl_rekomendasi)):null),
            'tgl_rekomendasi_c' => ($this->tgl_rekomendasi != null?date('d M Y', strtotime($this->tgl_rekomendasi)):"-"),
            'jam_rekomendasi_c' => ($this->tgl_rekomendasi != null?date('H:i', strtotime($this->tgl_rekomendasi)):"-"),
            'rekomendasiExist' => ($this->tgl_rekomendasi != null?true:false),
            'type' => $this->type,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'status' => $this->status,
            'status_sebelum' => $this->status,
            'list' => $this->list,
        ];
    }
}