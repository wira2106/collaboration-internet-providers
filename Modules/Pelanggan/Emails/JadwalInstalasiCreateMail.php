<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class JadwalInstalasiCreateMail extends Mailable implements ShouldQueue
{
   use Queueable, SerializesModels, InteractsWithQueue;

   public $data;
   public $title;
   public $url;
   public $jadwals;
   public $company_name;
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
      $this->jadwals = $data['jadwals'];
      $this->company_name = $data['name'];
      $this->pelanggan = $data['pelanggan'];
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->view('pelanggan::mails.JadwalInstalasiCreateMail')
         ->subject(trans('pelanggan::installations.mail.insert.pengajuan jadwal.subject'));
   }
}
