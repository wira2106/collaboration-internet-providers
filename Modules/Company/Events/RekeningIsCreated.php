<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\BankAccount;
use Modules\Configuration\Entities\Bank;
use Modules\User\Entities\Sentinel\User;

class RekeningIsCreated
{
    use SerializesModels;

    private $rekening;
    private $data;
    private $bank;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(BankAccount $rekening, array $data)
    {
        $this->rekening = $rekening;
        $this->data = $data;
        $this->bank = Bank::find($rekening->bank_id);

        $this->log(Auth::user());
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function log(User $user)
    {
        $user->createLog(
            trans('company::rekening.logs.create.tipe'),
            trans('company::rekening.logs.create.description', [
                'bank' => $this->bank->namaBank,
                'nomor_rekening' => $this->rekening->rekening
            ]),
            $this->rekening->company_id
        );
    }
}
