<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PengajuanJadwalSurveyDisetujui extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $url;
    public $data;
    public $body;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pelanggan, $data)
    {
        $this->title = trans('pelanggan::salesorders.mail.pengajuan jadwal survey disetujui.title');
        $this->url = route('admin.pelanggan.form.step', [
            'pelanggan' => $pelanggan['pelanggan_id']
        ]);

        $this->body = trans('pelanggan::salesorders.mail.pengajuan jadwal survey disetujui.body', [
            'nama_pelanggan' => $pelanggan['nama_pelanggan'],
            'tgl' => date('d M Y', strtotime($data))
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pelanggan::mails.PengajuanJadwalSurveyDisetujui')
            ->subject(trans('pelanggan::salesorders.mail.pengajuan jadwal survey disetujui.subject'));
    }
}
