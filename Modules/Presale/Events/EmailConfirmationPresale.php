<?php

namespace Modules\Presale\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Presale\Emails\ConfirmationPresale;
use Modules\Wilayah\Entities\Wilayah;

class EmailConfirmationPresale extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $wilayah;

    private $mail;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Wilayah $wilayah)
    {
        $this->wilayah = $wilayah;
        $this->send_email();
    }

    public function send_email() {
        $emails = (new Company())->admin_email($this->wilayah->company_id);

        $data = [
            "title" => trans('presale::presales.mail.presale confirmation email.title'),
            "url" => route('admin.presale.presale.index', [
                'wilayah' => $this->wilayah->wilayah_id
            ]),
            'wilayah' => $this->wilayah->toArray(),
            'emails' => $emails,
        ];

        // $this->setAttributes($data);
        Mail::to($emails)->send(new ConfirmationPresale($data));
    }
    
}
