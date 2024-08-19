<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveActivationMail extends Mailable implements ShouldQueue
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
        return $this->view('pelanggan::mails.ApproveActivationMail')
                    ->subject(trans('pelanggan::activations.mail.title.aktivasi pelanggan'));
    }
}
