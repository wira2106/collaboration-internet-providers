<?php

namespace Modules\Wilayah\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use DB;

class WilayahDetailPengajuan extends Resource
{
    public function toArray($request)
    {
        foreach ($this->participant as $key => $value) {
            $value->logo_url = url("/uploadgambar/".$value->logo_perusahaan);
        }
        $send = [            
            "wilayah_id" => $this->wilayah_id,
            "company_id" => $this->company_id,
            "name" => $this->name,
            "provinces_id" => ucwords($this->provinces_id),
            "regencies_id" => ucwords($this->regencies_id),
            "districts_id" => ucwords($this->districts_id),
            "villages_id" => ucwords($this->villages_id),
            "post_code" => $this->post_code,
            "company_name" => $this->company_name,
            "site" => $this->site,
            "end_point" => $this->end_point,
            "participant" => $this->participant,
            "provinsi" => $this->provinsi,
            "kabupaten" => $this->kabupaten,
            "kecamatan" => $this->kecamatan,
            "kelurahan" => $this->kelurahan,
            "status" => ""
        ];

        $status = DB::table("request_wilayah")->where("isp_id",Auth::User()->company()->company_id)
                            ->where('wilayah_id',$this->wilayah_id)
                            ->orderBy('request_wilayah_id','desc')
                            ->first();
        if ($status != null) {
            $send["status"] = $status->status;
        }
          
        return $send;
    }
}