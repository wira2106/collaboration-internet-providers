<?php

namespace Modules\Analytic\Events\Pelanggan;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use Modules\Analytic\Entities\Analytic;
use DB;

class SurveyPelanggan
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
        $this->surveyPelanggan();
        
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function surveyPelanggan()
    {
        $pelanggan_id = $this->pelanggan_id;
        $pelanggan = Pelanggan::find($pelanggan_id);
        $company_id = $pelanggan->osp_id;
        // start time
        $created_at = $this->getTanggalSurvey($pelanggan_id);
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
            'activity_tipe_id' => 3,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total_waktu' => $total_waktu,
            'created_by' => Auth::User()->id
        ]);
    }

    public function getTanggalSurvey($pelanggan_id)
    {
        $survey = DB::table('survey')->where('pelanggan_id',$pelanggan_id)->first();
        
        return $survey->tgl_survey;
    }
}
