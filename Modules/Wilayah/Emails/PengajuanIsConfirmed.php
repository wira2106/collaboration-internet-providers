<?php

namespace Modules\Wilayah\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PengajuanIsConfirmed extends Mailable
{
    use Queueable, SerializesModels;


    public $title;
    public $url;
    public $wilayah;
    public $body;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($wilayah, $osp)
    {
        $this->url = route('admin.wilayah.pengajuan.detail', [
            'id' => $wilayah->request_wilayah_id
        ]);

        $this->body = trans('wilayah::pengajuans.mail.pengajuan is confirmed.body', [
            'wilayah_name' => $wilayah->name,
            'osp_name' => $osp->name
        ]);
        
        $this->title = trans('wilayah::pengajuans.mail.pengajuan is confirmed.title');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('wilayah::mail.PengajuanIsConfirmed')
                    ->subject(trans('wilayah::pengajuans.mail.pengajuan is confirmed.subject'));
    }
}
