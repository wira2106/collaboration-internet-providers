<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\WorkingDay;
use Illuminate\Support\Facades\Auth;
class WorkDayIsUpdated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    private $company;
    private $data;
    public function __construct(Company $company, array $data)
    {
        //
        $this->company = $company;
        $this->data = $data['working_day'];
        $this->update();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    public function update() {
        $array = [];
        $companyId = $this->company->getKeyName();
        WorkingDay::where("$companyId", $this->company->company_id)->delete();
        
        foreach ($this->data as $key => $value) {
            $timeStart = $value['jam_mulai'];
            $timeEnd   = $value['jam_selesai'];
            $day = $value['day'];
            $array[] = [
                "hari" => $day,
                "company_id" => $this->company->company_id,
                'jam_mulai' => $timeStart,
                'jam_selesai' => $timeEnd,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => Auth::user()->id
            ];
        }

        WorkingDay::insert($array);

        Auth::user()->createLog(
            trans('company::workday.logs.edit.tipe'), 
            trans('company::workday.logs.edit.description'),
            $this->company->company_id
        );
    }
}
