<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Company\Entities\BiayaKabel;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class BiayaKabelIsCreating extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $biaya_kabel;
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(BiayaKabel $biayaKabel, array $data)
    {
        //
        $this->biaya_kabel = $biayaKabel;
        $this->data = $data;
        parent::__construct($data);
    }

    public function check_data() {
        $biaya_kabel = BiayaKabel::where('company_id', $this->getAttribute('company_id'))
                        ->where('panjang_kabel', $this->getAttribute('panjang_kabel'))
                        ->where('range_id', '!=', $this->getAttribute('range_id'))
                        ->first();
        if($biaya_kabel) {
            return abort(409, $biaya_kabel);
        }
    }
}
