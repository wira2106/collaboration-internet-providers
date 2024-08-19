<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PelangganTransformers extends Resource
{
    public function toArray($request)
    {
        $pemakaian = 100;
        $colorPemakaian = '#e6a23c';
        $send = [
            'pelanggan_id' => $this->pelanggan_id,
            'alasan_suspend' => $this->alasan_suspend,
            'nama_pelanggan' => $this->nama_pelanggan,
            'site_id' => $this->site_id,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'nama_paket' => $this->nama_paket,
            'cancel' => $this->cancel,
            'status' => $this->status,
            'address' => $this->address,
            'harga_paket' => 'Rp. ' . number_format($this->biaya_mrc, 0, ',', '.'),
            'isp_name' => $this->isp_name,
            'wilayah_name' => $this->wilayah_name,
            'osp_name' => $this->osp_name,
            'pemakaian' => $pemakaian,
            'colorPemakaian' => $colorPemakaian,
            'bulan' => date('M-Y'),
            'alasan_cancel' => $this->alasan_cancel,
            'status_perubahan_harga' => isset($this->status_perubahan_harga) ? $this->status_perubahan_harga : null,
            'pengajuan_jadwal_instalasi_pending' => isset($this->pengajuan_jadwal_instalasi_pending) ? $this->pengajuan_jadwal_instalasi_pending : 0,
            'pengajuan_jadwal_survey_pending' => isset($this->pengajuan_jadwal_survey_pending) ? $this->pengajuan_jadwal_survey_pending : 0,
            // 'wilayah_name' => $this->wilayah_name." (".$this->osp_name.")"
            'urls' => [
                'delete_url' => $this->generate_delete_url()
            ]
        ];

        if (isset($this->jumlah_jadwal)) {
            $send['jumlah_jadwal'] = $this->jumlah_jadwal;
        } else if (isset($this->status_survey)) {
            $send['status_survey'] = $this->status_survey;
        } else if (isset($this->status_instalasi)) {
            $send['status_instalasi'] = $this->status_instalasi;
        } else if (isset($this->status_aktivasi)) {
            $send['status_aktivasi'] = $this->status_aktivasi;
        }

        return $send;
    }

    private function generate_delete_url()
    {
        if (($this->status === 'so' || $this->status === 'survey' || $this->status === 'instalasi') && $this->cancel === 1) {
            return route('api.pelanggan.destroy', $this->pelanggan_id);
        }


        return null;
    }
}
