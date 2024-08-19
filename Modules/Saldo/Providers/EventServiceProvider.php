<?php

namespace Modules\Saldo\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Saldo\Events\Handlers\SendEmailTopupConfirmed;
use Modules\Saldo\Events\Handlers\SendEmailTopupCreated;
use Modules\Saldo\Events\TopupIsConfirmed;
use Modules\Saldo\Events\TopUpIsCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TopUpIsCreated::class => [
            SendEmailTopupCreated::class,
        ],
        TopupIsConfirmed::class => [
            SendEmailTopupConfirmed::class,
        ]
    ];
}
