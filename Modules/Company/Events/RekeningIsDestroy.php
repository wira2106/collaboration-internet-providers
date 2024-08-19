<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\BankAccount;
use Modules\Configuration\Entities\Bank;
use Modules\User\Entities\Sentinel\User;

class RekeningIsDestroy
{
    use SerializesModels;

    private $rekening;
    private $bank;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(BankAccount $rekening)
    {
        $this->rekening = $rekening;
        $this->bank = Bank::find($rekening->bank_id);

        $this->log(Auth::user());
    }

    private function log(User $user) {
        $user->createLog(
            trans('company::rekening.logs.destroy.tipe'),
            trans('company::rekening.logs.destroy.description',[
                'bank' => $this->bank->namaBank,
                'nomor_rekening' => $this->rekening->rekening
            ]),
            $this->rekening->company_id
        );
    }

}
