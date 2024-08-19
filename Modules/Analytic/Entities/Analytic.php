<?php

namespace Modules\Analytic\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use DB;

class Analytic extends Model
{
    protected $table = 'activity_timer';
    protected $primaryKey = "activity_id";
    public $timestamps = false;
    protected $fillable = [
        'company_id',
        'pelanggan_id',
        'activity_tipe_id',
        'start_time',
        'end_time',
        'total_waktu',
        'created_by'
    ];


    public function getActivityPelanggan($pelanggan_id)
    {
        $data = Self::where("pelanggan_id",$pelanggan_id)
                ->select([
                    "activity_timer.*",
                    "activity_tipe.nama_activity",
                    "companies.type",
                    DB::raw("SUM(total_waktu) as total_waktu")
                ])->groupBy('activity_timer.activity_tipe_id')
                ->orderBy('activity_tipe.activity_tipe_id','asc')
                ->join('companies','activity_timer.company_id','companies.company_id')
                ->join('activity_tipe','activity_timer.activity_tipe_id','activity_tipe.activity_tipe_id')
                ->get();
        
        return $data;
    }

    public function listActivityInstalasiPelanggan($company_id, $request)
    {
       // data select
		$select = [
			'pelanggan.pelanggan_id',
			'pelanggan.biaya_mrc',
			'pelanggan.nama_pelanggan',
			'pelanggan.telepon',
            'pelanggan.created_at',
            'pelanggan.tipe_ep',
            'pelanggan.estimasi_instalasi',
			'email',
			'presales.site_id',
			'presales.address',
			'paket_berlangganan.nama_paket',
			'pelanggan.status',
			'pelanggan.cancel',
			'suspend.alasan as alasan_suspend',
			'isp.name as isp_name',
			'osp.name as osp_name',
			'wilayah.name as wilayah_name',
            DB::raw("(select end_time from activity_timer where activity_timer.pelanggan_id = pelanggan.pelanggan_id order by activity_id desc limit 1) as tanggal_akhir")
		];

		// default query
		$data = DB::table('pelanggan')
			->leftJoin('suspend','suspend.suspend_id','=','pelanggan.suspend_id')
			->join('presales', 'presales.presale_id', '=', 'pelanggan.presale_id')
			->join('wilayah', 'wilayah.wilayah_id', '=', 'presales.wilayah_id')
			->join('paket_berlangganan', 'paket_berlangganan.paket_id', '=', 'pelanggan.paket_id')
			->join('companies as isp', 'isp.company_id', '=', 'pelanggan.isp_id')
			->join('companies as osp', 'osp.company_id', '=', 'pelanggan.osp_id')
            ->whereIn('pelanggan.status',['aktif','cancel']);
                
        if ($company_id != null && $company_id != "") {           
            $data = $data->where('isp.company_id', $company_id);
        }

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $data->where(function ($query) use ($term) {
                $query->orWhere('pelanggan.nama_pelanggan','LIKE',"%{$term}%")
                ->orWhere('presales.site_id','LIKE',"%{$term}%")
                ->orWhere('isp.name','LIKE',"%{$term}%")
                ->orWhere('osp.name','LIKE',"%{$term}%")
                ->orWhere('wilayah.name','LIKE',"%{$term}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';
            $data->orderBy($request->get('order_by'), $order);
        } else {
            $data->orderBy('pelanggan.nama_pelanggan', 'asc');
        }

        // deleted at
		$data->whereNull('pelanggan.deleted_at')->where('pelanggan.status', 'aktif')->select($select);
        return $data->paginate($request->get('per_page', 10));
    }

    public static function getLastActivityAndTimerOSP($pelanggan_id)
    {
        $pelanggan = Pelanggan::find($pelanggan_id);
        $data = self::where('pelanggan_id',$pelanggan_id)->select(['activity_timer.*','companies.type'])
                ->join('companies','companies.company_id','activity_timer.company_id')
                ->orderBy('activity_id','desc')->get();
        $activity = [];
        foreach ($data as $key => $value) {
            $activity[] = $value;
        }

        $pause = false;
        $jumlah_timer_sebelumnya = 0;
        if(count($activity) == 0){
            $last_date = date('Y-m-d H:i:s', strtotime($pelanggan->created_at));
        }else{
            $last_activity = $activity[0];
            $last_activity_isp = null;
            // mencari tanggal terakhir dari ISP dan hitung total waktu OSP
            foreach ($data as $key => $val) {
                if($last_activity_isp == null && $val->type == 'isp'){
                    $last_activity_isp = date('Y-m-d H:i:s', strtotime($val->end_time));
                }
                if($val->type == 'osp'){
                    $jumlah_timer_sebelumnya = $jumlah_timer_sebelumnya + $val->total_waktu;
                }
            }

            // jika tidak ada tanggal terakhir ISP, pakai tanggal created at dari pelanggan
            if($last_activity_isp == null){
                $last_activity_isp = date('Y-m-d H:i:s', strtotime($pelanggan->created_at));
            }

            $last_date = $last_activity_isp;
            if(in_array($last_activity->activity_tipe_id, [1,3,5,8])){
                $pause = true;
            }
        }

        $send_date = Company::getJamKerjaCompanySaatProses($pelanggan->osp_id, $last_date);
        if(strtotime($last_date) > strtotime($send_date)){
            $send_date = $last_date;
        }

        $diff = 0;
        if(!$pause){
            $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($send_date);
        }
        $diff = $diff + $jumlah_timer_sebelumnya;
        $sisa_waktu = $pelanggan->estimasi_instalasi - $diff;

        $send = [
            'pause' => $pause,
            'sisa_waktu' => $sisa_waktu,
            'status_pelanggan' => $pelanggan->status
        ];
        return $send;
    }
    
}
