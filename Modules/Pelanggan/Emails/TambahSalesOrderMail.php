<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Traits\ConvertToCurrency;

class TambahSalesOrderMail extends Mailable implements ShouldQueue
{
    use Queueable, ConvertToCurrency;

    public $title;
    public $data;
    public $url;
    public $paket;
    public $pelanggan;
    public $address;
    public $nama_pelanggan;
    public $telepon;
    public $harga_paket;
    public $biaya_otc;
    public $nama_paket;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct(array $data, $pelanggan)
    {
        $this->url = $data['url'];
        $this->data = $data;
        $this->title = trans('pelanggan::salesorders.mail.insert.title');
        $this->harga_paket = $this->rupiah($pelanggan['paketBerlangganan']['harga_paket']);
        $this->biaya_otc = $this->rupiah($pelanggan['paketBerlangganan']['biaya_otc']);
        $this->nama_paket = $pelanggan['paketBerlangganan']['nama_paket'];
        $this->address = $pelanggan['address'];
        $this->nama_pelanggan = $pelanggan['nama_pelanggan'];
        $this->telepon = $pelanggan['telepon'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pelanggan::mails.TambahSalesOrder')
                    ->subject(trans('pelanggan::salesorders.mail.insert.subject'));
    }
}
