<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\User\Entities\Sentinel\User;

class CompanyIsUpdated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $company;
    private $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company, array $data)
    {
        $this->company = $company;
        $this->data = $data;
        $this->log(Auth::user());
    }

    public function log(User $user)
    {
        $user->createLog(
            trans('company::companies.logs.edit.tipe'),
            trans('company::companies.logs.edit.description', [
                'company_name' => $this->company->name
            ])
        );
    }
}
