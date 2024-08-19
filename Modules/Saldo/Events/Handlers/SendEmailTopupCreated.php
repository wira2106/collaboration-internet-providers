<?php

namespace Modules\Saldo\Events\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Saldo\Emails\TopupCreatedMail;
use Modules\Saldo\Events\TopUpIsCreated;

class SendEmailTopupCreated
{
    use ConvertToCurrency;

    private $data;
    private $mail;

    public function __construct(Mailer $mail)
    {
        $this->mail = $mail;
    }

    public function handle(TopUpIsCreated $event)
    {

        $emails = $event->getAttribute('emails');
        $data = [
            "title" => trans('saldo::saldos.mail.topup saldo created.title'),
            "url" => route('admin.saldo.topup.index'),
            "total" => $this->rupiah($event->getAttribute('amount')),
            "company_name" => $event->getAttribute('company')['company_name'],
            "data" => $event->getAttributes(),
        ];
        $this->mail->to($emails)->send(new TopupCreatedMail($data));
        // Mail::send('saldo::mail.topup_created', $data, function($message) use ($emails) {
        //     $message->to($emails)->subject(trans('saldo::saldos.mail.topup saldo created.subject'));
        // });
    }
}
