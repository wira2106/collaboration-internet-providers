<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class HistorySLApelanggan extends Resource
{
  public function toArray($request)
  {
    $lama_mati = '';
    $jam = floor($this->total_koneksi_mati / 60);
    $menit = $this->total_koneksi_mati % 60;

    if ($jam != 0) {
      $lama_mati .= $jam . " Jam ";
    }
    if ($menit != 0) {
      $lama_mati .= $menit . " Menit";
    }
    $v = $this;
    return [
      'nama_pelanggan'    => (isset($v->nama_pelanggan) ? $v->nama_pelanggan : null),
      'nama_paket'    => (isset($v->nama_paket) ? $v->nama_paket : null),
      'biaya_mrc'    => (isset($v->biaya_mrc) ? number_format($v->biaya_mrc) : null),
      'total_pengurangan_tagihan'    => (isset($v->total_pengurangan_tagihan) ? number_format($v->total_pengurangan_tagihan) : null),
      'persentase_toleransi'    => (isset($v->persentase_toleransi) ? $v->persentase_toleransi . '%' : null),
      'persentase_total_koneksi_mati'    => (isset($v->persentase_total_koneksi_mati) ? floor($v->persentase_total_koneksi_mati) . '%' : null),
      'sla_paket'    => (isset($v->sla_paket) ? $v->sla_paket . '%' : null),
      'total_koneksi_mati'    => $lama_mati,
      'nama_paket'    => (isset($v->nama_paket) ? $v->nama_paket : null),
      'bulan'    => (isset($v->created_at) ? date("M-Y", strtotime($v->created_at)) : null),
    ];
  }
}
