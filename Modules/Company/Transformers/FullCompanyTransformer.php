<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class FullCompanyTransformer extends Resource
{
    public function toArray($request)
    {
        if($this->ppn == 0){
            $ppn = false;
        }else{
            $ppn = true;
        }

        return [
            'company_id' => $this->company_id,
            'name' => $this->name,
            'type' => $this->type,
            'provinces_id' => $this->provinces_id,
            'regencies_id' => $this->regencies_id,
            'districts_id' => $this->districts_id,
            'villages_id' => $this->villages_id,
            'address' => $this->address,
            'pop_address' => $this->pop_address,
            'post_code' => $this->post_code,
            'display_name' => $this->display_name,
            'logo_perusahaan' => null,
            'company_img' => $this->logo_perusahaan ? url("/uploadgambar") . '/' .  $this->logo_perusahaan : "",
            'pop_lat' => $this->pop_lat ? $this->pop_lat : 0,
            'pop_lon' => $this->pop_lon ? $this->pop_lon : 0,
            'company_lat' => $this->company_lat ? $this->company_lat : 0, 
            'company_lon' => $this->company_lon ? $this->company_lon : 0, 
            'total_endpoint' => $this->total_endpoint,
            'rating' => $this->rating,
            'ppn' => $ppn
            // 'urls' => [
            //     'delete_url' => route('api.user.user.destroy', $this->id),
            // ],
        ];
    }
}