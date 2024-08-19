<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\WorkingDay;

class WorkDayIsDestroy
{
    use SerializesModels;

    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WorkingDay $working_day)
    {
        Auth::user()->createLog(
            trans('company::workday.logs.destroy.tipe'),
            trans('company::workday.logs.destroy.description', [
                'data' => $working_day->hari
            ]),
            $working_day->company_id
        );
    }

}
