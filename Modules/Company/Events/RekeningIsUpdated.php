<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\BankAccount;
use Modules\Configuration\Entities\Bank;
use Modules\User\Entities\Sentinel\User;

class RekeningIsUpdated
{
    use SerializesModels;

    private $rekening;
    private $data;
    private $bank_before;
    private $bank;
    private $original;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BankAccount $rekening, array $data)
    {
        $this->rekening = $rekening;
        $this->original = $rekening->getOriginal();
        $this->data = $data;
        $this->bank_before = Bank::find($this->original['bank_id']);
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
            trans('company::rekening.logs.edit.tipe'),
            trans('company::rekening.logs.edit.description', [
                'bank_before' => $this->bank_before['namaBank'],
                'nomor_rekening_before' => $this->original['rekening'],
                'bank' => $this->bank->namaBank,
                'nomor_rekening' => $this->rekening->rekening
            ]),
            $this->rekening->company_id
        );
    }
}
