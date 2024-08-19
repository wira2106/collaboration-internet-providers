<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class PelangganInstalasi extends Resource
{
    public function toArray($request)
    {
        return [
            'cancel' => $this->cancel,
            'alasan_cancel' => $this->alasan_cancel,
            'pelanggan_id' => $this->pelanggan_id,
            'nama_pelanggan' => $this->nama_pelanggan,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'jenis_identitas' => $this->jenis_identitas,
            'nomor_identitas' => $this->nomor_identitas,
            'foto_identitas' => url("/uploadgambar") . '/' . $this->foto_identitas,
            'foto_url' => url('/uploadgambar') . '/' . $this->foto_identitas,
            'status_kepemilikan' => ucfirst($this->status_kepemilikan),
            'status' => $this->status,
            'nama_paket' => (isset($this->nama_paket_perubahan) ? $this->nama_paket_perubahan : $this->nama_paket),
            'biaya_mrc' => 'Rp. ' . number_format($this->biaya_mrc, 0, ',', '.'),
            'biaya_otc' => 'Rp. ' . number_format($this->biaya_otc, 0, ',', '.'),
        ];
    }
}
