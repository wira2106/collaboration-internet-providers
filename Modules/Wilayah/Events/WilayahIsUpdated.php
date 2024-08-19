<?php

namespace Modules\Wilayah\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Presale\Events\EmailConfirmationPresale;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Entities\Wilayah;

class WilayahIsUpdated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $wilayah;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Wilayah $wilayah, $data )
    {
        $this->wilayah = $wilayah;
        $this->log( Auth::user() );
        parent::__construct($data);

        if($this->getAttribute('status_presale') == 'review') $this->send_mail_review();
    }

    public function log( User $user )
    {
        $user->createLog(
            trans('wilayah::wilayahs.logs.edit.tipe'),
            trans('wilayah::wilayahs.logs.edit.description', [
                'wilayah' => $this->wilayah->name
            ]),
            $this->wilayah->company_id
        );
    }

    public function send_mail_review() {
        event(new EmailConfirmationPresale($this->wilayah));
    }
}
