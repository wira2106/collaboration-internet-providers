<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderSurvey extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $title;
    public $url;
    public $body;
    public $company_name;
    public $nama_pelanggan;
    public $tgl_survey;
    public $address;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->title = trans('pelanggan::pelanggans.mails.reminder survey.title');
        
        $this->url = route('admin.pelanggan.form.step', [
            'pelanggan' => $data['pelanggan_id']
        ]);

        $this->body = trans('pelanggan::pelanggans.mails.reminder survey.body', [
                        'nama_pelanggan' => $data['nama_pelanggan'],
                        'tanggal' => $data['tgl_survey']
                    ]);
        
        $this->company_name = $data['osp_name'];
        $this->nama_pelanggan = $data['nama_pelanggan'];
        $this->tgl_survey = $data['tgl_survey'];
        $this->address = $data['address'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pelanggan::mails.ReminderSurvey')
                    ->subject(trans('pelanggan::pelanggans.mails.reminder survey.subject'));
    }
}
