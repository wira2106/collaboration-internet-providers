<?php

namespace Modules\Saldo\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawIsConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $url;
    public $title;
    public $data;
    public $email_body;
    public $bank;
    public $status;
    public $nama_bank;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email_body = $data['email_body'];
        $this->subject = $data['subject'];
        $this->title = $data['title'];
        $this->url = $data['url'];
        $this->data = $data;
        $this->bank = $data['bank'];
        $this->status = $data['status'];
        $this->nama_bank = $this->bank['namaBank'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $build = $this->view('saldo::mail.withdraw_confirm')
                        ->subject($this->subject);
                        
        if(isset($this->data['status']) && $this->data['status'] != 'cancel') {
            $build->attach(public_path('uploadgambar/'. $this->data['bukti_transfer']));
        }
        return $build;
    }
}
