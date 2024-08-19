<?php

namespace Modules\Analytic\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Core\Traits\ConvertToTimer;

class ListAnalyticInstalasiPelanggan extends Resource
{
    use ConvertToTimer;

    public function toArray($request)
    {       
        $standar = $this->convertToTime($this->estimasi_instalasi,true);
        $estimasi = $this->convertToTime(strtotime($this->tanggal_akhir) - strtotime($this->created_at), true);
    
        $send = [
            'pelanggan_id' => $this->pelanggan_id,
            'alasan_suspend' => $this->alasan_suspend,
            'nama_pelanggan'=>$this->nama_pelanggan,
            'site_id'=>$this->site_id,
            'telepon'=>$this->telepon,
            'email'=>$this->email,
            'nama_paket'=>$this->nama_paket,
            'cancel'=>$this->cancel,
            'status'=>$this->status,
            'address' => $this->address,
            'harga_paket' => 'Rp. '.number_format($this->biaya_mrc,0,',','.'),
            'isp_name' => $this->isp_name,
            'wilayah_name' => $this->wilayah_name,
            'osp_name' => $this->osp_name,
            'standar' => $standar,
            'estimasi' => $estimasi,
            'tipe_ep' => $this->tipe_ep
        ]; 

        return $send;
    }
}
