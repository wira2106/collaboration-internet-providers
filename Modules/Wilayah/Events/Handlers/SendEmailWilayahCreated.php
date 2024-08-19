<?php

namespace Modules\Wilayah\Events\Handlers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailWilayahCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $data;
    public $title;
    public $url;
    
    public function __construct(array $data)
    {   
        $this->data = $data;
        $this->title = $data['title'];
        $this->url = $data['url'];
    }


    public function build() {
        return $this->view('wilayah::mail.wilayah_created')
                    ->subject(trans('wilayah::wilayahs.mail.wilayah created.subject'));
    }
}
