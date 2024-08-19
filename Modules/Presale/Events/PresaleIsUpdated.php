<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Presale\Entities\Presale;
use Modules\User\Entities\Sentinel\User;

class PresaleIsUpdated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $presale;
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Presale $presale, array $data)
    {
        parent::__construct($data);
        $this->presale = $presale;
        $this->data    = $data;
        if ($this->getAttribute('pathline')) $this->presale->updatePathLine($this->getAttribute('pathline'));
        $this->log(Auth::user());
    }

    public function log(User $user)
    {
        $user->createLog(
            trans('presale::presales.logs.edit.tipe'), 
            trans('presale::presales.logs.edit.description', [
                'data' => $this->presale->site_id
            ])
        );
    }
}
