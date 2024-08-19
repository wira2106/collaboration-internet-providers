<?php

namespace Modules\Saldo\Events;

use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Saldo\Entities\topup;

class TopupIsConfirmed extends AbstractEntityHook implements EntityIsChanging
{
    use ConvertToCurrency;

    private $data;
    private $company;
    private $topup;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company,topup $topup, array $data)
    {
        $this->data = $data;
        $this->company = $company;
        $this->topup = $topup;

        parent::__construct($data);
        $this->send_email();
    }
    
    public function send_email() {
        $emails = $this->company->admin_email();
        $data = [
            "title" => trans('saldo::saldos.mail.topup saldo created.title'),
            "url" => route('admin.saldo.topup.index'),
            "total" => $this->rupiah($this->topup->amount) ,
            "company" => Company::find($this->getAttribute('company_id')),
            
        ];
        if($this->getAttribute('status') !== 'cancel') {
            $data['email_body'] = trans('saldo::saldos.mail.topup is confirmation.confirmed body', [
                'company_name' => $this->company->name,
                'total' => $this->rupiah($this->topup->amount) 
            ]);
            $subject = trans('saldo::saldos.mail.topup is confirmation.confirmed subject');
        } else {
            $subject = trans('saldo::saldos.mail.topup is confirmation.rejected subject');
            $data['email_body'] = trans('saldo::saldos.mail.topup is confirmation.rejected body', [
                'company_name' => $this->company->name,
                'total' => $this->rupiah($this->topup->amount),
                'alasan' => $this->getAttribute('keterangan')
            ]);
        }
        $data['subject'] = $subject;
        $data['emails'] = $emails;

        $this->setAttributes($data);
    }
    
}
