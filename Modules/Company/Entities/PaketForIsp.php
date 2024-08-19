<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Company\Entities\DiskonPaketBerlangganan;
use DB;
use Auth;
use Modules\Core\Traits\ConvertToCurrency;

class PaketForIsp extends Model
{
  use ConvertToCurrency;
  protected $table = 'paket_for_isp';
  protected $fillable = ['isp_id', 'paket_id'];

  public function getPaketBerlanggananForISP($wilayah_id,$isp_id=null)
  {
    if ($isp_id == null) {
      $isp_id = Auth::User()->company()->company_id;
    }
    $select = ['b.harga_paket','b.nama_paket','b.sla','b.paket_id','b.biaya_otc'];
    $paket = DB::table('paket_for_isp as a')->select($select)
        ->join('paket_berlangganan as b','a.paket_id','=','b.paket_id')
        ->join('request_wilayah as c','a.request_wilayah_id','=','c.request_wilayah_id')
        ->where('c.wilayah_id',$wilayah_id)
        ->where('isp_id',$isp_id)
        ->get();

    foreach ($paket as $key => $val) {
      $select = ['diskon','start_pelanggan','end_pelanggan'];        
      $diskon = DiskonPaketBerlangganan::where('paket_id',$val->paket_id)->select($select)->get();
      foreach ($diskon as $key => $v) {
        $v->diskon = $this->hitungHargaSetelahDiskon($val->harga_paket,$v->diskon);
      }
      $val->diskon = $diskon;
      $val->harga_paket_rp = $this->rupiah($val->harga_paket);
    }

    return $paket;
  }

  function hitungHargaSetelahDiskon($harga,$diskon)
  {
    return ($harga - round(($harga*$diskon)/100));
  }
}
