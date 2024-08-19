<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Presale\Entities\Presale;
use Modules\User\Entities\Sentinel\User;

class PresaleIsDestroy 
{
    private $presale;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Presale $presale)
    {
        $this->presale = $presale;
        $this->log(Auth::user());
    }

    public function log(User $user)
    {
        $user->createLog(
            trans('presale::presales.logs.destroy.tipe'),
            trans('presale::presales.logs.destroy.description', [
                'data' => $this->presale->site_id
            ])
        );
    }
}
