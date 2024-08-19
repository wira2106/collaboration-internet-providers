<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class CompanyIsUpdating extends AbstractEntityHook implements EntityIsChanging
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
        $this->log =  Auth::user();
        parent::__construct($data);
    }

    public function getCompany()
    {
        return $this->company;
    }
}
