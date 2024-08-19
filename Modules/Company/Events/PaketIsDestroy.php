<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\User\Entities\Sentinel\User;

class PaketIsDestroy
{
    use SerializesModels;

    private $paket_berlangganan;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaketBerlangganan $paket_berlangganan)
    {
        $this->paket_berlangganan = $paket_berlangganan;
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
            trans('company::paketberlangganans.logs.destroy.tipe'), 
            trans('company::paketberlangganans.logs.destroy.description', [
                'data' => $this->paket_berlangganan->nama_paket
                ])
            );
    }
}
