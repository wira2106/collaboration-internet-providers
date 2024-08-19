<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class JadwalInstalasiPilihMail extends Mailable implements ShouldQueue
{
   use Queueable, SerializesModels, InteractsWithQueue;

   public $data;
   public $title;
   public $url;
   public $pelanggan;

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
      return $this->view('pelanggan::mails.JadwalInstalasiPilihMail')
         ->subject(trans('pelanggan::installations.mail.select.pengajuan jadwal.subject'));
   }
}
