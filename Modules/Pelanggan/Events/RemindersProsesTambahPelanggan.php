<?php

namespace Modules\Pelanggan\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Emails\ReminderInstalasi;
use Modules\Pelanggan\Emails\ReminderPilihTanggalInstalasi;
use Modules\Pelanggan\Emails\ReminderPilihTanggalSurvey;
use Modules\Pelanggan\Emails\ReminderSurvey;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Pelanggan\Entities\PengajuanJadwal;
use Modules\Pelanggan\Entities\Survey;

class RemindersProsesTambahPelanggan
{
    use SerializesModels;
    private $pelanggan;
    private $company_admin_mail;
    private $company_teknisi_mail;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->remindersPilihTanggalSurvey();
        $this->remindersPilihTanggalInstalasi();
        $this->remindersBesokSurvey();
        $this->remindersBesokInstalasi();

    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    private function remindersPilihTanggalSurvey()
    {
        $pelanggan_so = PengajuanJadwal::select([
                                            'pelanggan.osp_id',
                                            'pelanggan.nama_pelanggan',
                                            'pengajuan_jadwal.*'
                                        ])
                                        ->where('cancel', 0)
                                        ->where('pengajuan_jadwal.status', 'pending')
                                        ->where('pengajuan_jadwal.type', 'survey')
                                        ->where('tgl_rekomendasi', 'like', date('Y-m-d', strtotime(date('Y-m-d') . '+1 day')) . '%')
                                        ->join('pelanggan', 'pelanggan.pelanggan_id', 'pengajuan_jadwal.pelanggan_id')
                                        ->get();

        
        foreach($pelanggan_so as $key => $pelanggan) {
            // dd($this->getCompanAdminMail($pelanggan->osp_id)); 
            if(($this->getCompanyAdminMail($pelanggan->osp_id)) > 0) {
                Mail::to($this->getCompanyAdminMail($pelanggan->osp_id))->send(new ReminderPilihTanggalSurvey($pelanggan));
            }
        }

    }


    private function remindersPilihTanggalInstalasi() {
        $pelanggan_survey = PengajuanJadwal::select([
                                            'pelanggan.osp_id',
                                            'pelanggan.nama_pelanggan',
                                            'pengajuan_jadwal.*'

                                        ])
                                        ->where('cancel', 0)
                                        ->where('pengajuan_jadwal.status', 'pending')
                                        ->where('pengajuan_jadwal.type', 'instalasi')
                                        ->where('tgl_rekomendasi', 'like', date('Y-m-d', strtotime(date('Y-m-d') . '+1 day')) . '%')
                                        ->join('pelanggan', 'pelanggan.pelanggan_id', 'pengajuan_jadwal.pelanggan_id')
                                        ->get();
        // dd($pelanggan_survey);
        foreach($pelanggan_survey as $key => $pelanggan) {
            if(count($this->getCompanyAdminMail($pelanggan->osp_id)) > 0) {
                Mail::to($this->getCompanyAdminMail($pelanggan->osp_id))->send(new ReminderPilihTanggalInstalasi($pelanggan));
            }
        }
    }

    private function remindersBesokSurvey() {
        // dd(date('Y-m-d', strtotime(date('Y-m-d') . '+1 day')));
        $pelanggan_survey = Survey::select([
                                        'pelanggan.osp_id',
                                        'pelanggan.nama_pelanggan',
                                        'pelanggan.pelanggan_id',
                                        'companies.name as osp_name',
                                        'survey.tgl_survey',
                                        'presales.address'
                                    ])
                                    ->where('survey.status', 'active')
                                    ->where('survey.tgl_survey', 'like', date('Y-m-d', strtotime(date('Y-m-d') . '+1 day')) . '%')
                                    ->join('pelanggan', 'pelanggan.pelanggan_id', 'survey.pelanggan_id')
                                    ->join('companies', 'companies.company_id', 'pelanggan.osp_id')
                                    ->join('presales', 'presales.presale_id', 'pelanggan.presale_id')
                                    ->get();
                                
        // dd($pelanggan_survey);
        foreach ($pelanggan_survey as $key => $pelanggan) {
            $teknisi = array();
            $teknisi = $this->getCompanyTeknisiMail($pelanggan->osp_id);

            if($teknisi && count($teknisi) > 0) {
                Mail::to($teknisi)->send(new ReminderSurvey($pelanggan));
            }
        }
    }

    private function remindersBesokInstalasi() {
        $pelanggan_instalasi = DB::table('instalasi')->select([
                                        'pelanggan.osp_id',
                                        'pelanggan.nama_pelanggan',
                                        'pelanggan.pelanggan_id',
                                        'companies.name as osp_name',
                                        'instalasi.tgl_instalasi',
                                        'presales.address'
                                    ])
                                    ->where('instalasi.status', 'active')
                                    ->where('instalasi.tgl_instalasi', 'like', date('Y-m-d', strtotime(date('Y-m-d') . '+1 day')) . '%')
                                    ->join('pelanggan', 'pelanggan.pelanggan_id', 'instalasi.pelanggan_id')
                                    ->join('companies', 'companies.company_id', 'pelanggan.osp_id')
                                    ->join('presales', 'presales.presale_id', 'pelanggan.presale_id')
                                    ->get()->toArray();
        foreach ($pelanggan_instalasi as $key => $pelanggan) {
            $teknisi = array();
            $teknisi = $this->getCompanyTeknisiMail($pelanggan->osp_id);


            if($teknisi && count($teknisi) > 0) {
                Mail::to($teknisi)->send(new ReminderInstalasi((array)$pelanggan));
            }
        }
        
    }

    private function getCompanyAdminMail($company_id) {
            //code...
        if($this->company_admin_mail[$company_id]) return $this->company_admin_mail[$company_id];

        $this->company_admin_mail[$company_id] = (new Company())->admin_email($company_id);

        return $this->company_admin_mail[$company_id];
    }

    private function getCompanyTeknisiMail($company_id) {
        if($this->company_teknisi_mail[$company_id]) return $this->company_admin_mail[$company_id];

        $this->company_teknisi_mail[$company_id] = (new Company())->teknisi_email($company_id);

        return $this->company_teknisi_mail[$company_id];
    }
}
