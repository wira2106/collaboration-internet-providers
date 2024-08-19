<?php

namespace Modules\Wilayah\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class NotifPengajuanWilayah extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (Auth::user()->company()->type == 'osp') {
            return $this->subject('Notification Pengajuan Wilayah OSP')
                ->view('mail::admin.pengajuan_wilayah.isp')
                ->with('mail', $this->data);
        } else {
            return $this->subject('Notification Pengajuan Wilayah ISP')
                ->view('mail::admin.pengajuan_wilayah.osp')
                ->with('mail', $this->data);
        }
    }
}
