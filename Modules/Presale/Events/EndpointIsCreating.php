<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Presale\Entities\Endpoint;

class EndpointIsCreating extends AbstractEntityHook implements EntityIsChanging
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
    }

}
