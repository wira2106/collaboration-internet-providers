<?php

namespace Modules\Wilayah\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestWilayahMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $url;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->title = $data['title'];
        $this->url = $data['url'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('wilayah::mail.request_wilayah')
                    ->subject(trans('wilayah::wilayahs.mail.request presale successfull.subject'));
    }
}
