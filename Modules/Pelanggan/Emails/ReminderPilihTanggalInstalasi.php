<?php

namespace Modules\Pelanggan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderPilihTanggalInstalasi extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $title;
    public $url;
    public $body;
    public $nama_pelanggan;
    public $tanggalRequest;
    public $keterangan;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->title = trans('pelanggan::pelanggans.mails.reminder pilih tanggal instalasi.title');

        $this->url = route('admin.pelanggan.form.step', [
            'pelanggan' => $data['pelanggan_id']
        ]);

        $this->body = trans('pelanggan::pelanggans.mails.reminder pilih tanggal instalasi.body', [
                        'nama_pelanggan' => $data['nama_pelanggan']
                    ]);
        
        $this->nama_pelanggan = $data['nama_pelanggan'];
        $this->tanggalRequest = $data['tgl_rekomendasi'];
        $this->keterangan = $data['keterangan'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pelanggan::mails.ReminderPilihTanggalInstalasi')
                    ->subject(trans('pelanggan::pelanggans.mails.reminder pilih tanggal instalasi.subject'));
    }
}
