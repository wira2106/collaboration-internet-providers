<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Presale\Entities\Presale;
use Modules\User\Entities\Sentinel\User;

class PresaleIsCreated extends AbstractEntityHook implements EntityIsChanging
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
        //
        $this->presale = $presale;
        $this->data = $data;
        parent::__construct($data);
        if($this->getAttribute('pathline')) $this->presale->updatePathLine($this->getAttribute('pathline'));
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
            trans('presale::presales.logs.create.tipe'), 
            trans('presale::presales.logs.create.description', [
                'data' => $this->presale->site_id
            ])
        );
    }
}
