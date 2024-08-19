<?php

namespace Modules\Saldo\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $url;
    public $total;
    public $company;
    public $data;
    public $bank;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->url = $data['url'];
        $this->total = $data['total'];
        $this->company = $data['company'];
        $this->data = $data;
        $this->bank = $data['bank'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('saldo::mail.withdraw_created')
                    ->subject(trans('saldo::saldos.mail.withdraw created.subject'));
    }
}
