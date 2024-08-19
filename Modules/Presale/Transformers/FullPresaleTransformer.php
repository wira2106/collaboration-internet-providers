<?php

namespace Modules\Presale\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class FullPresaleTransformer extends Resource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'address' => $this->address, 
            'biaya_instalasi' => $this->biaya_instalasi, 
            'biaya_kabel' => $this->biaya_kabel, 
            'class_id' => $this->class_id, 
            'created_at' => $this->created_at, 
            'created_by' => $this->created_by, 
            'deleted_at' => $this->deleted_at, 
            'districts_id' => $this->districts_id, 
            'end_point_id' => $this->end_point_id, 
            'endpoint_name' => $this->endpoint_name, 
            'icon' => $this->icon, 
            'keterangan' => $this->keterangan, 
            'kode_pos' => $this->kode_pos, 
            'lat' => $this->lat, 
            'lat_ep' => $this->lat_ep, 
            'lon' => $this->lon, 
            'lon_ep' => $this->lon_ep, 
            'nama_gang' => $this->nama_gang, 
            'no_rumah' => $this->no_rumah, 
            'panjang_kabel' => $this->panjang_kabel, 
            'path' => $this->path, 
            'presale_id' => $this->presale_id, 
            'provinces_id' => $this->provinces_id, 
            'regencies_id' => $this->regencies_id, 
            'site_id' => $this->site_id, 
            'status_id' => $this->status_id, 
            'total_biaya' => $this->total_biaya, 
            'updated_at' => $this->updated_at, 
            'updated_by' => $this->updated_by, 
            'villages_id' => $this->villages_id, 
            'wilayah_id' => $this->wilayah_id,
            'presale_img' => url('/uploadgambar') . '/' . $this->foto_rumah,
            'edited_text' => $this->edited_text
        ];
    }




}
