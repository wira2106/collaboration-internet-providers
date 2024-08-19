<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Presale\Entities\Endpoint;
use Modules\User\Entities\Sentinel\User;

class EndpointIsDestroy
{
    use SerializesModels;
    private $endpoint;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint =  $endpoint;
        $this->log(Auth::user());
    }

    public function log(User $user) {
        $user->createLog(
            trans('presale::endpoint.logs.destroy.tipe'), 
            trans('presale::endpoint.logs.destroy.description', [
                'data' => $this->endpoint->nama_end_point
            ])
        );
    }
}
