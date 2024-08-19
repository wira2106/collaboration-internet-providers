<?php

namespace Modules\Saldo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\BankAccount;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Saldo\Emails\WithdrawCreatedMail;
use Modules\User\Entities\Sentinel\User;

class WithdrawIsCreated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels, ConvertToCurrency;

    private $data;
    private $company;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company,array $data)
    {
        $this->data = $data;
        $this->company = $company;
        parent::__construct($data);
        $this->send_email();
        $this->log(Auth::user());
    }
    
    public function send_email() {
        $emails = $this->company->admin_email(1);

        $bank = BankAccount::where('bank_account_id', $this->getAttribute('bank_account_id'))
                            ->join('bank', 'bank.bank_id', 'bank_account.bank_id')
                            ->first();
        $data = [
            "title" => trans('saldo::saldos.mail.withdraw created.title'),
            "url" => route('admin.saldo.withdraw.index'),
            "total" => $this->rupiah($this->getAttribute('amount')),
            "company" => $this->company,
            'bank' => $bank
        ];
        Mail::to($emails)->send(new WithdrawCreatedMail($data));
    }

    public function log(User $user) {
        $user->createLog(
            trans('saldo::withdraws.logs.create.tipe'), 
            trans('saldo::withdraws.logs.create.description')
        );
    }
    
}
