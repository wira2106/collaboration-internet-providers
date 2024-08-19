<?php

namespace Modules\Saldo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Saldo\Entities\Saldo;
use Modules\Saldo\Entities\topup;
use Modules\User\Entities\Sentinel\User;

class TopupIsCancel
{
    use SerializesModels, ConvertToCurrency;
    private $company;
    private $topup;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company, topup $topup)
    {
        $this->company = $company;
        $this->topup = $topup;
        $this->log(Auth::user());
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function log( User $user )
    {
        // dd($user);
        $user->createLog(
            trans('saldo::topups.logs.reject.tipe'),
            trans('saldo::topups.logs.reject.description', [
                'user' => $this->company->name,
                'data' => $this->rupiah($this->topup->amount)
            ])
        );
    }
}
