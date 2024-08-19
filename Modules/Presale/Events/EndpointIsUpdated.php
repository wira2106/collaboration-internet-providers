<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Presale\Entities\Endpoint;
use Modules\User\Entities\Sentinel\User;

class EndpointIsUpdated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $endpoint;
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Endpoint $endpoint, array $data)
    {
        $this->endpoint = $endpoint;
        $this->data = $data;
        parent::__construct($data);
        $this->endpoint->generateJarakBaruDenganPresale($this->getAttribute('lat_end_point'), $this->getAttribute('lon_end_point'));
        $this->log(Auth::user());
    }

    public function log(User $user) {
        $user->createLog(
            trans('presale::endpoint.logs.edit.tipe'), 
            trans('presale::endpoint.logs.edit.description', [
                'data' => $this->getAttribute('nama_end_point')
            ])
        );
    }
}
