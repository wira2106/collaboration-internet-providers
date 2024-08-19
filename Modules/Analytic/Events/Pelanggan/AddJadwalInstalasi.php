<?php

namespace Modules\Analytic\Events\Pelanggan;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use Modules\Analytic\Entities\Analytic;
use DB;

class AddJadwalInstalasi
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
        $this->accPerubahanHarga();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function accPerubahanHarga()
    {
        $pelanggan_id = $this->pelanggan_id;
        $pelanggan = Pelanggan::find($pelanggan_id);
        $company_id = $pelanggan->osp_id;
        // start time
        $created_at = $this->getCreatedAtFromLastPengajuan($pelanggan_id);
        $start_time = Company::getJamKerjaCompanySaatProses($company_id, $created_at);
        if (strtotime($created_at) > strtotime($start_time)) {
            $start_time = $created_at;
        }
        // end time
        $end_time = date('Y-m-d H:i:s');
        // total waktu
        $total_waktu = strtotime($end_time) - strtotime($start_time);
        $analytic = Analytic::create([
            'company_id' => $company_id,
            'pelanggan_id' => $pelanggan_id,
            'activity_tipe_id' => 5,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total_waktu' => $total_waktu,
            'created_by' => Auth::User()->id,
        ]);
    }

    public function getCreatedAtFromLastPengajuan($pelanggan_id)
    {
        $data = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan_id)
            ->where('type', 'instalasi')
            ->orderBy('pengajuan_id', 'desc')
            ->first();

        return $data->created_at;
    }
}
