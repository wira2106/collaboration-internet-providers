<?php

namespace Modules\Saldo\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Saldo\Events\Handlers\RegisterSaldoSidebar;

class SaldoServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, $this->getSidebarClassForModule('saldo', RegisterSaldoSidebar::class));

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load("saldos", array_dot(trans("saldo::saldos")));
            $event->load("mutasi", array_dot(trans("saldo::mutasi")));
            $event->load("topups", array_dot(trans("saldo::topups")));
            $event->load("withdraws", array_dot(trans("saldo::withdraws")));
        });
    }

    public function boot()
    {
        $this->publishConfig('saldo', 'permissions');

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
            'Modules\Saldo\Repositories\topupRepository',
            function () {
                $repository = new \Modules\Saldo\Repositories\Eloquent\EloquenttopupRepository(new \Modules\Saldo\Entities\topup());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Saldo\Repositories\Cache\CachetopupDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Saldo\Repositories\withdrawRepository',
            function () {
                $repository = new \Modules\Saldo\Repositories\Eloquent\EloquentwithdrawRepository(new \Modules\Saldo\Entities\withdraw());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Saldo\Repositories\Cache\CachewithdrawDecorator($repository);
            }
        );
// add bindings


    }
}
