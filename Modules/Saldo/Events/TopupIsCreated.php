<?php

namespace Modules\Saldo\Events;

use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\BankAccount;
use Modules\Company\Entities\Company;
use Modules\Configuration\Entities\Bank;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\User\Entities\Sentinel\User;

class TopUpIsCreated extends AbstractEntityHook implements EntityIsChanging
{
    use ConvertToCurrency;

    private $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        
        $this->data = $data;
        parent::__construct($data);
        $bank_pengirim = Bank::find($this->getAttribute('bank_id'));
        $penerima = BankAccount::where('bank_account.bank_account_id', $this->getAttribute('bank_account_id'))
                                ->join('bank','bank.bank_id', 'bank_account.bank_id')
                                ->first();

        $this->setAttributes([
            'company' => Company::find($this->getAttribute('company_id')),
            'emails' => (new Company())->admin_email(1),
            'penerima' => $penerima,
            'bank_pengirim' => $bank_pengirim
        ]);
        $this->log(Auth::user());
    }
    
    public function log(User $user) {
        $user->createLog(
            trans('saldo::topups.logs.create.tipe'), 
            trans('saldo::topups.logs.create.description')
        );
    }

    
}
