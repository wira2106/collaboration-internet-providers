<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Pelanggan\Entities\Pelanggan;

class ticketSupportBulanIniTransformer extends Resource
{
  public function toArray($request)
  {
    $pelanggan = new Pelanggan();
    $total = $pelanggan->rangeEstimasiInstalasi($this->start_gangguan, $this->end_gangguan, 'Menit');

    $lama_mati = '';
    $jam = floor($total['total_menit'] / 60);
    $menit = $total['total_menit'] % 60;

    if ($jam != 0) {
      $lama_mati .= $jam . " Jam ";
    }
    if ($menit != 0) {
      $lama_mati .= $menit . " Menit";
    }
    $v = $this;
    return [
      'start_gangguan'    => (isset($v->start_gangguan) ? date("d-M-Y H:i:s", strtotime($v->start_gangguan))  : null),
      'end_gangguan'    => (isset($v->end_gangguan) ? date("d-M-Y H:i:s", strtotime($v->end_gangguan)) : null),
      'total_koneksi_mati'    => $lama_mati,
    ];
  }
}
