<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Traits\ConvertToCurrency;

class RequestPengubahanJadwal extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, ConvertToCurrency;

    public $pelanggan;
    public $perubahan_harga;
    public $title;
    public $url;
    public $body;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pelanggan, $perubahan_harga)
    {
        $this->pelanggan = $pelanggan;
        $this->perubahan_harga = $perubahan_harga;
        $this->body = trans('pelanggan::surveys.mails.pengajuan perubahan harga.body', [
            'nama_pelanggan' => $pelanggan['nama_pelanggan'],
            'mrc' => $this->rupiah($this->perubahan_harga['harga_mrc']),
            'otc' => $this->rupiah($this->perubahan_harga['harga_otc']),
        ]);

        $this->url = route('admin.pelanggan.form.step', [
            'pelanggan' => $pelanggan['pelanggan_id']
        ]);

        $this->title = trans('pelanggan::surveys.mails.pengajuan perubahan harga.title');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pelanggan::mails.RequestPengubahanHarga')
                    ->subject(trans('pelanggan::surveys.mails.pengajuan perubahan harga.subject'));
    }
}
