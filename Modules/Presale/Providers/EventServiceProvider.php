<?php

namespace Modules\Presale\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Presale\Events\EndpointIsCreated;
use Modules\Presale\Events\Handlers\SendEmailEndpointCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        EndpointIsCreated::class => [
            SendEmailEndpointCreated::class,
        ],
    ];
}
