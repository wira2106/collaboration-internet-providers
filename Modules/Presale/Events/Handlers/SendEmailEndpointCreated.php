<?php

namespace Modules\Presale\Events\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Presale\Emails\EndpointCreatedMail;
use Modules\Presale\Events\EndpointIsCreated;
class SendEmailEndpointCreated 
{
    use ConvertToCurrency;

    private $mail;

    public function __construct(Mailer $mail)
    {
        $this->mail = $mail;
    }

    public function handle(EndpointIsCreated $event)
    {

        $emails = $event->getAttribute('emails');

        $data = [
            "title" => trans('presale::endpoint.mail.endpoint created.title'),
            "url" => route('admin.presale.presale.index'),
            "biaya" => $this->rupiah($event->getAttribute('biaya')),
            'data' => $event->getAttribute('endpoint')
        ];

        
        $this->mail->to($emails)->send(new EndpointCreatedMail($data));
    }
}
