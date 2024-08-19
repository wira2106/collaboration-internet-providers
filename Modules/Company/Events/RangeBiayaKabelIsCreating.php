<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Company\Entities\BiayaKabel;
use Modules\Company\Entities\RangeBiayaKabel;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class RangeBiayaKabelIsCreating extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $biaya_kabel;
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(RangeBiayaKabel $biayaKabel, array $data)
    {
        //
        $this->biaya_kabel = $biayaKabel;
        $this->data = $data;
        parent::__construct($data);
    }

    public function check_data() {
        $biaya_kabel = RangeBiayaKabel::where('biaya_kabel_id', $this->getAttribute('biaya_kabel_id'))
                        ->where('panjang_kabel', $this->getAttribute('panjang_kabel'))
                        ->where('range_id', '!=', $this->biaya_kabel->range_id)
                        ->first();
                      
        if($biaya_kabel) {
            return abort(409, $biaya_kabel);
        }
    }
}
