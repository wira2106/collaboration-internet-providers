<?php

namespace Modules\Saldo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\User\Entities\Sentinel\User;

class TopupIsUpdated
{
    use SerializesModels;

    private $company;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->log(Auth::user());
        
    }

    public function log(User $user)
    {
        $user->createLog(
            trans('saldo::topups.logs.edit.tipe'), 
            trans('saldo::topups.logs.edit.description', [
                    'user' => $this->company->name
                ]
            )
        );
    }
}
