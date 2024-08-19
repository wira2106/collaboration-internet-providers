<?php

namespace Modules\Ticket\Emails\sla;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketApproveSendMail extends Mailable implements ShouldQueue
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
        $email =  $this->view('ticket::mail.sla.ticket_approve')
                    ->subject(trans('ticket::tickets.title.tickets'));

        return $email;
    }
}