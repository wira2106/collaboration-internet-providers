<?php

namespace Modules\Presale\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Presale\Events\Handlers\RegisterPresaleSidebar;

class PresaleServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
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
        $this->app['events']->listen(BuildingSidebar::class, $this->getSidebarClassForModule("presale", RegisterPresaleSidebar::class));

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load("presales", array_dot(trans("presale::presales")));
            $event->load("endpoint", array_dot(trans("presale::endpoint")));
        });
    }

    public function boot()
    {
        $this->publishConfig('presale', 'permissions');

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
            'Modules\Presale\Repositories\presaleRepository',
            function () {
                $repository = new \Modules\Presale\Repositories\Eloquent\EloquentpresaleRepository(new \Modules\Presale\Entities\Presale());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Presale\Repositories\Cache\CachepresaleDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Presale\Repositories\endpointRepository',
            function () {
                $repository = new \Modules\Presale\Repositories\Eloquent\EloquentendpointRepository(new \Modules\Presale\Entities\Endpoint());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Presale\Repositories\Cache\CacheendpointDecorator($repository);
            }
        );
// add bindings


    }
}
