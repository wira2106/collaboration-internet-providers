<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DetailSalesOrderTransformer extends Resource
{
  public function toArray($request)
  {
    $v = $this;
    return [
      'pelanggan_id'      => (isset($v->pelanggan_id) ? $v->pelanggan_id : null),
      'presale_id'        => (isset($v->presale_id) ? $v->presale_id : null),
      'isp_id'            => (isset($v->isp_id) ? $v->isp_id : null),
      'osp_id'            => (isset($v->osp_id) ? $v->osp_id : null),
      'paket_id'          => (isset($v->paket_id) ? (int)$v->paket_id : null),
      'biaya_mrc'         => (isset($v->biaya_mrc) ? $v->biaya_mrc : 0),
      'biaya_otc'         => (isset($v->biaya_otc) ? $v->biaya_otc : 0),
      'biaya_mrc_with_format'         => number_format((isset($v->biaya_mrc) ? $v->biaya_mrc : 0)),
      'biaya_otc_with_format'         => number_format((isset($v->biaya_otc) ? $v->biaya_otc : 0)),
      'nama_pelanggan'    => (isset($v->nama_pelanggan) ? $v->nama_pelanggan : null),
      'telepon'           => (isset($v->telepon) ? $v->telepon : null),
      'email'             => (isset($v->email) ? $v->email : null),
      'status_kepemilikan' => (isset($v->status_kepemilikan) ? $v->status_kepemilikan : null),
      'jenis_identitas'   => (isset($v->jenis_identitas) ? $v->jenis_identitas : ''),
      'nomor_identitas'   => (isset($v->nomor_identitas) ? $v->nomor_identitas : ''),
      'foto_identitas'    => (isset($v->foto_identitas) && $v->foto_identitas != null ? url('/uploadgambar/' . $v->foto_identitas) : null),
      'suspended_at'      => (isset($v->suspended_at) ? $v->suspended_at : null),
      'alasan_cancel'     => (isset($v->alasan_cancel) ? $v->alasan_cancel : null),
      'foto_jalur_ep'     => (isset($v->foto_jalur_ep) && $v->foto_jalur_ep != null ? url('/uploadgambar/' . $v->foto_jalur_ep) : null),
      'site_id'           => $v->site_id,
      'status_pelanggan'  => $v->status,
      'lat'               => $v->lat,
      'lon'               => $v->lon,
      'address'           => (isset($v->address) ? $v->address : ''),
      'foto_rumah'        => (isset($v->foto_rumah) && $v->foto_rumah != null ? url('/uploadgambar/' . $v->foto_rumah) : null),
      'icon'              => (isset($v->icon) ? $v->icon : null),
      'lat_ep'            => (isset($v->lat_ep) ? $v->lat_ep : 0),
      'lon_ep'            => (isset($v->lon_ep) ? $v->lon_ep : 0),
      'endpoint_name'     => (isset($v->endpoint_name) ? $v->endpoint_name : null),
      'type_ep'           => (isset($v->type_ep) ? $v->type_ep : null),
      'icon_ep'           => (isset($v->icon_ep) ? $v->icon_ep : null),
      'wilayah_id'        => (isset($v->wilayah_id) ? $v->wilayah_id : 0),
      'tgl_survey'        => (isset($v->tgl_survey) ? $v->tgl_survey : null),
      'path'              => (isset($v->path) ? $v->path : null),
      'cancel'            => $v->cancel
    ];
  }
}
