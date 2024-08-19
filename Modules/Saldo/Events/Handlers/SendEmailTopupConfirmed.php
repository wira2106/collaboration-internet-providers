<?php

namespace Modules\Saldo\Events\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Saldo\Emails\TopupConfirmed;
use Modules\Saldo\Entities\topup;
use Modules\Saldo\Events\TopupIsConfirmed;

class SendEmailTopupConfirmed
{
    use ConvertToCurrency;

    private $mail;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Mailer $mail)
    {
        $this->mail = $mail;
    }

    public function handle(TopupIsConfirmed $event)
    {

        $this->mail->to($event->getAttribute('emails'))->send(new TopupConfirmed($event->getAttributes($event)));
    }
}
