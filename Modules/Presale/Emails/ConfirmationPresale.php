<?php

namespace Modules\Presale\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmationPresale extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $title;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->url = isset($data['url']) ? $data['url'] : '';
        $this->title = isset($data['title']) ? $data['title'] : '';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('presale::mail.presale_confirmation')
                    ->subject(trans('presale::presales.mail.presale confirmation email.subject', ['wilayah' => $this->data['wilayah']['name']]));
    }
}
