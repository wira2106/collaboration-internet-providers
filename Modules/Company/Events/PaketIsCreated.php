<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\User\Entities\Sentinel\User;

class PaketIsCreated
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

    public function log(User $user)
    {
        $user->createLog(
            trans('company::paketberlangganans.logs.create.tipe'), 
            trans('company::paketberlangganans.logs.create.description', [
                'data' => $this->paket_berlangganan->nama_paket
            ]),
            $this->paket_berlangganan->company_id
        );
    }
}
