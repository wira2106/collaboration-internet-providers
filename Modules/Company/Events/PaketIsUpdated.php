<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\User\Entities\Sentinel\User;

class PaketIsUpdated
{
    use SerializesModels;

    private $paket_berlangganan;
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaketBerlangganan $paket_berlangganan, array $data)
    {
        $this->paket_berlangganan = $paket_berlangganan;
        $this->data = $data;
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
            trans('company::paketberlangganans.logs.edit.tipe'), 
            trans('company::paketberlangganans.logs.edit.description', [
                'data' => $this->paket_berlangganan->nama_paket
            ]),
            $this->paket_berlangganan->company_id
        );
    }
}
