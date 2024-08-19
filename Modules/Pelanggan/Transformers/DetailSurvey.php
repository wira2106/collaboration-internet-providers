<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DetailSurvey extends Resource
{
  public function toArray($request)
  {
    $foto_jalur_kabel = [];
    $foto_signal_kabel = [];
    // cek list foto jalur kabel
    if ($this->foto_jalur_kabel !== null) {
      foreach (json_decode($this->foto_jalur_kabel) as $key => $value) {
        $foto_jalur_kabel[] = (object)[
          'name' => $value,
          'url' => url('/uploadgambar/' . $value),
          'new' => false
        ];
      }
    }
    // end cek list foto jalur kabel
    // cek list foto signal kabel
    if ($this->foto_signal_kabel !== null) {
      foreach (json_decode($this->foto_signal_kabel) as $key => $value) {
        $foto_signal_kabel[] = (object)[
          'name' => $value,
          'url' => url('/uploadgambar/' . $value),
          'new' => false
        ];
      }
    }
    // end cek list foto signal kabel
    return [
      'id' => $this->id,
      'pelanggan_id' => $this->pelanggan_id,
      'tgl_survey' => $this->tgl_survey,
      'tinggi_bangunan' => $this->tinggi_bangunan,
      'satuan_tinggi' => $this->satuan_tinggi,
      'foto_jalur_kabel' => $foto_jalur_kabel,
      'foto_signal_kabel' => $foto_signal_kabel,
      'keterangan' => $this->keterangan,
      'status' => $this->status,
      'id_survey' => $this->id_survey,
      'survey_id' => $this->survey_id,
      // 'teknisi_id' => $this->teknisi_id,
      'perangkat_id' => $this->perangkat_id,
      'qty_perangkat' => $this->qty_perangkat,
      'jenis_perangkat' => $this->jenis_perangkat,
      'status_pelanggan' => $this->status_pelanggan,
    ];
  }
}
