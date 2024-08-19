<?php

namespace Modules\Saldo\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopupConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $title;
    public $url;
    public $email_body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        
        $this->data = $data;
        $this->title = isset($data['title']) ? $data['title'] : '';
        $this->url = isset($data['url']) ? $data['url'] : '';
        $this->email_body = isset($data['email_body']) ? $data['email_body'] : '';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('saldo::mail.topup_confirm')
                    ->subject($this->data['subject']);
    }
}
