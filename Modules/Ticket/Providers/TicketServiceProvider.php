<?php

namespace Modules\Ticket\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Ticket\Events\Handlers\RegisterTicketSidebar;

class TicketServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterTicketSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('tickets', array_dot(trans('ticket::tickets')));
            $event->load('ticketsSuspend', array_dot(trans('ticket::ticketsSuspend')));
            $event->load('ticketsSla', array_dot(trans('ticket::ticketsSla')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('ticket', 'permissions');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Ticket\Repositories\TicketRepository',
            function () {
                $repository = new \Modules\Ticket\Repositories\Eloquent\EloquentTicketRepository(new \Modules\Ticket\Entities\Ticket());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Ticket\Repositories\Cache\CacheTicketDecorator($repository);
            }
        );
// add bindings

    }
}