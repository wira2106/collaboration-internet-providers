<?php

namespace Modules\Ticket\Emails\suspend;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessagesSendMail extends Mailable implements ShouldQueue
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
        $this->data = $data['data'];
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
        $email =  $this->view('ticket::mail.suspend.messages_created')
                    ->subject(trans('ticket::tickets.title.tickets'));
        $attachments = json_decode($this->data['messages']['attachments']);
        foreach($attachments as $filePath){
            $email->attach(public_path('uploadgambar/'.$filePath));
        }

        return $email;
    }
}