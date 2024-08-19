<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Entities\Wilayah;

class PresaleIsConfirmed
{
    use SerializesModels;

    private $wilayah;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Wilayah $wilayah)
    {
        $this->wilayah = $wilayah;
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
            trans('presale::presales.logs.confirmed.tipe'),
            trans('presale::presales.logs.confirmed.description', [
                'data' => $this->wilayah->name
            ])
        );
    }
}
