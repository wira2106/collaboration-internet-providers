<?php

namespace Modules\Mail\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class NotifikasiWilayah extends Mailable implements ShouldQueue
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
        if ($this->data['type_company'] == 'osp') {
            return $this->subject('Notification Pengajuan Wilayah')
                ->view('mail::admin.pengajuan_wilayah.isp')
                ->with('mail', $this->data);
        } else {
            return $this->subject('Notification Pengajuan Wilayah')
                ->view('mail::admin.pengajuan_wilayah.osp')
                ->with('mail', $this->data);
        }
    }
}
