<?php

namespace Modules\Analytic\Events\Pelanggan;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use Modules\Analytic\Entities\Analytic;
use DB;

class PenentuanJadwalInstalasi
{
    use SerializesModels;

    private $pelanggan_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($pelanggan_id)
    {
        $this->pelanggan_id = $pelanggan_id;
        $this->penentuanJadwalInstalasi();
        
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function penentuanJadwalInstalasi()
    {
        $pelanggan_id = $this->pelanggan_id;
        $pelanggan = Pelanggan::find($pelanggan_id);
        $company_id = $pelanggan->isp_id;
        // start time
        $created_at = $this->getCreatedAtFromPengajuanInstalasi($pelanggan_id);
        $start_time = Company::getJamKerjaCompanySaatProses($company_id, $created_at);
        if(strtotime($created_at) > strtotime($start_time)){
            $start_time = $created_at;
        }
        // end time
        $end_time = date('Y-m-d H:i:s');
        // total waktu
        $total_waktu = strtotime($end_time) - strtotime($start_time);

        Analytic::create([
            'company_id' => $company_id,
            'pelanggan_id' => $pelanggan_id,
            'activity_tipe_id' => 6,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total_waktu' => $total_waktu,
            'created_by' => Auth::User()->id,
        ]);
    }

    public function getCreatedAtFromPengajuanInstalasi($pelanggan_id)
    {
        $pengajuan = DB::table('tanggal_pengajuan_instalasi as a')->select('a.created_at')
                    ->join('pengajuan_jadwal as b','b.pengajuan_id','a.pengajuan_id')
                    ->where('b.pelanggan_id',$pelanggan_id)
                    ->orderBy('a.pengajuan_id','desc')
                    ->first();
        
        return $pengajuan->created_at;
    }
}
