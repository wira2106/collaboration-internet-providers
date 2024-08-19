<?php

namespace Modules\Saldo\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopupCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $title;
    public $url;
    public $bank;
    public $penerima;
    public $bank_penerima;
    
    public function __construct(array $data)
    {   
        $this->data = $data;
        $this->title = $data['title'];
        $this->url = $data['url'];
        $this->bank = $data['data']['bank_pengirim'];
        $this->penerima = $data['data']['penerima'];
        $this->bank_penerima = $data['data']['penerima']['namaBank'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('saldo::mail.topup_created')
                    ->subject(trans('saldo::saldos.mail.topup saldo created.subject'));
    }
}
