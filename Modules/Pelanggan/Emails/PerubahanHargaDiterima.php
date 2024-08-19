<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Traits\ConvertToCurrency;

class PerubahanHargaDiterima extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, ConvertToCurrency;
    public $body;
    public $url;
    public $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pelanggan)
    {
        $this->title = trans('pelanggan::surveys.mails.pengajuan perubahan harga diterima.title');
        $this->url = route('admin.pelanggan.form.step', [
            'pelanggan' => $pelanggan['pelanggan_id']
        ]);
        $this->body = trans('pelanggan::surveys.mails.pengajuan perubahan harga diterima.body', [
            'nama_pelanggan' => $pelanggan['nama_pelanggan'],
            'mrc' => $this->rupiah($pelanggan['biaya_mrc']),
            'otc' => $this->rupiah($pelanggan['biaya_otc'])
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pelanggan::mails.PerubahanHargaDiterima')
                    ->subject(trans('pelanggan::surveys.mails.pengajuan perubahan harga diterima.subject'));
    }
}
