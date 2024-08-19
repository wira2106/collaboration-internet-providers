<?php

namespace Modules\Saldo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Saldo\Entities\withdraw;
use Modules\User\Entities\Sentinel\User;

class WithdrawIsCancel
{
    use SerializesModels;

    private $company;
    private $tarik_saldo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company, withdraw $tarik_saldo)
    {
        $this->company = $company;
        $this->tarik_saldo = $tarik_saldo;
        $this->log(Auth::user());

    }

    public function log(User $user)
    {
        $user->createLog(
            trans('saldo::withdraws.logs.reject.tipe'), 
            trans('saldo::withdraws.logs.reject.description', [
                'user' => $this->company->name,
                'data' => $this->tarik_saldo->amount
            ])
        );
    }
}
