<?php

namespace Modules\Invoice\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $body;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($refund_item)
    {
        $this->title = trans('invoice::invoices.mails.refund email.title');
        $this->url  = route('admin.invoice.invoices.index');
        
        $this->body = $refund_item->description;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('invoice::mails.refund')
                    ->subject(trans('invoice::invoices.mails.refund email.subject'));
    }
}
