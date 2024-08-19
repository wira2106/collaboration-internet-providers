<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengajuan extends Model
{
	protected $table = 'request_wilayah';
	protected $primaryKey = "request_wilayah_id";
	protected $fillable = [
		'isp_id',
		'osp_id',
		'wilayah_id',
		'status',
		'alasan',
		'accepted_at',
		'created_at'
	];
  public $timestamps = false;


  public function detailPengajuan($id)
  {
    $select = ['a.*', 'b.name as nama_osp', 'c.name as nama_isp', 'd.name as nama_wilayah', 'e.*'];
    $data    = DB::table('request_wilayah as a')->select($select)
      ->join('wilayah as d', 'd.wilayah_id', '=', 'a.wilayah_id')
      ->join('companies as b', 'b.company_id', '=', 'a.osp_id')
      ->join('companies as c', 'c.company_id', '=', 'a.isp_id')
      ->leftjoin('kontrak_wilayah as e', 'e.request_wilayah_id', '=', 'a.request_wilayah_id')
      ->where('a.request_wilayah_id', $id)
      ->where('e.active', 1)
      ->first();
    return $data;
  }

  public static function detailWilayahByPengajuan($id)
  {
    $data = DB::table('request_wilayah as a')->select('b.*','c.name as nama_osp')
            ->join('wilayah as b','a.wilayah_id','b.wilayah_id')
            ->join('companies as c','b.company_id','c.company_id')
            ->where('a.request_wilayah_id',$id)
            ->first();
    
    return $data;
  }
}
