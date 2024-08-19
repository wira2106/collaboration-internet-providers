<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\DiskonPaketBerlangganan;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\User\Entities\Sentinel\User;

class DiskonPaketIsUpdated
{
    use SerializesModels;

    private $diskonPaketBerlangganan;
    private $paket_berlangganan;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DiskonPaketBerlangganan $diskonPaketBerlangganan)
    {
        $this->diskonPaketBerlangganan = $diskonPaketBerlangganan;
        $this->paket_berlangganan = PaketBerlangganan::find($this->diskonPaketBerlangganan->paket_id);
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
            trans('company::diskon_paket.logs.edit.tipe'), 
            trans('company::diskon_paket.logs.edit.description', [
                'paket' => $this->paket_berlangganan->nama_paket,
            ]),
            $this->paket_berlangganan->company_id
        );
    }
}
