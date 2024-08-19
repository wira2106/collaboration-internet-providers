<?php

namespace Modules\Wilayah\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use DB;
use Modules\Wilayah\Traits\TransformStatusRequestWilayah;

class ListPengajuanTransformer extends Resource
{
    use TransformStatusRequestWilayah;
    public function toArray($request)
    {

        $end_date = strtotime(Date($this->end_date));
        $now_date = strtotime(Date('Y-m-d'));
        $sisa_hari = ($end_date-$now_date) / (60*60*24);

        if($sisa_hari > 30){
            $sisa_bulan = $sisa_hari/30;
        }else{
            $sisa_bulan = 0;
            $sisa_hari;
        }
        
        

        return [
            "request_wilayah_id" => $this->request_wilayah_id,
            "isp_id" => $this->isp_id,
            "osp_id" => $this->osp_id,
            "wilayah_id" => $this->wilayah_id,
            "status" => $this->status,
            "alasan" => $this->alasan,
            "created_at" => date('d M Y H:i', strtotime($this->created_at)),
            "end_date_day" => $sisa_hari,
            "end_date_month"=> round($sisa_bulan),
            "nama_osp" => $this->nama_osp,
            "nama_isp" => $this->nama_isp,
            "nama_wilayah" => $this->nama_wilayah,
            "toleransi" => $this->toleransi_tunggakan,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            "file_kontrak" => $this->file_kontrak,
            "deskripsi" => $this->deskripsi_kontrak,
            'readable_status' => $this->transform_status($this->status),
        ];
    }

    
}
