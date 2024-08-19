<?php

namespace Modules\Wilayah\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Wilayah\Entities\Wilayah;

class WilayahIsOpenMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $isp_name;
    public $osp_name;
    public $wilayah_name;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Wilayah $wilayah, $admin)
    {
        $this->isp_name = $admin->company_name;
        $this->osp_name = $wilayah['company_name'];
        $this->wilayah_name = $wilayah['wilayah_name'];
        $this->url = route('admin.wilayah.pengajuan.index');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('wilayah::mail.wilayah_open')
                    ->subject(trans('wilayah::wilayahs.mail.wilayah open.subject'));
    }
}
