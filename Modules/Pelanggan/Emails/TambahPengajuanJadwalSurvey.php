<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TambahPengajuanJadwalSurvey extends Mailable implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $pelanggan;
    public $jadwals;
    public $url;
    public $title;
    public $company_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pelanggan, $jadwals, $company_name)
    {
        $this->company_name = $company_name;
        $this->pelanggan = $pelanggan;
        $this->jadwals = $jadwals;
        $this->title = trans('pelanggan::salesorders.mail.tambah pengajuan jadwal survey.title');
        $this->url = route('admin.pelanggan.form.step', [
            'pelanggan' => $pelanggan['pelanggan_id']
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pelanggan::mails.TambahPengajuanJadwalSurvey')
            ->subject(trans('pelanggan::salesorders.mail.tambah pengajuan jadwal survey.subject'));
    }
}
