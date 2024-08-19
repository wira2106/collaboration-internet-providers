<?php

namespace Modules\Wilayah\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
class WilayahTransformer extends Resource
{
    public function toArray($request)
    {
        $send = [            
            "wilayah_id" => $this->wilayah_id,
            "company_id" => $this->company_id,
            "name" => $this->name,
            "open" => $this->open,
            "provinces_id" => $this->provinces_id,
            "regencies_id" => $this->regencies_id,
            "districts_id" => $this->districts_id,
            "villages_id" => $this->villages_id,
            "post_code" => $this->post_code,
            "company_name" => $this->company_name,
            'status_presale' => $this->status_presale
        ]; 

        if (isset($this->provinsi)) {
            $send["provinsi"] = $this->provinsi;
            $send["kabupaten"] = $this->kabupaten;
            $send["kecamatan"] = $this->kecamatan;
            $send["kelurahan"] = $this->kelurahan;
        }  
        return $send;
        
    }
}