<?php

namespace Modules\Presale\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EndpointIsCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $url;
    public $biaya;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data['data'];
        $this->biaya = $data['biaya'];
        $this->url = $data['url'];
        $this->title = $data['title'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('presale::mail.endpoint_created')
                    ->subject(trans('presale::endpoint.mail.endpoint created.subject'));
    }
}
