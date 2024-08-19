<?php

namespace Modules\Presale\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BayarEndpointMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $url;
    public $title;
    public $body;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($wilayah, $biaya)
    {
        $this->title = trans('presale::endpoint.mail.bayar endpoint.title');
        $this->url = route('api.presale.presales.index', [
            'wilayah' => $wilayah->wilayah_id
        ]);

        $this->body = trans('presale::endpoint.mail.bayar endpoint.sukses bayar endpoint', [
            'biaya' => $biaya,
            'wilayah_name' => $wilayah->name
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('presale::mail.bayar_endpoint')
                    ->subject(trans('presale::endpoint.mail.bayar endpoint.subject'));
    }
}
