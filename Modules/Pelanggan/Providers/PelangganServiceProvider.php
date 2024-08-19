<?php

namespace Modules\Pelanggan\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Pelanggan\Events\Handlers\RegisterPelangganSidebar;

class PelangganServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterPelangganSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('salesorders', array_dot(trans('pelanggan::salesorders')));
            $event->load('surveys', array_dot(trans('pelanggan::surveys')));
            $event->load('installations', array_dot(trans('pelanggan::installations')));
            $event->load('activations', array_dot(trans('pelanggan::activations')));
            $event->load('pelanggans', array_dot(trans('pelanggan::pelanggans')));
            $event->load('pengajuanjadwal', array_dot(trans('pelanggan::pengajuanjadwal')));
            $event->load('pelangganinstalasi', array_dot(trans('pelanggan::pelangganinstalasi')));
            $event->load('logpelanggan',array_dot(trans('pelanggan::logpelanggan')));
        });
    }

    public function boot()
    {
        $this->publishConfig('pelanggan', 'permissions');

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
            'Modules\Pelanggan\Repositories\SalesOrderRepository',
            function () {
                $repository = new \Modules\Pelanggan\Repositories\Eloquent\EloquentSalesOrderRepository(new \Modules\Pelanggan\Entities\SalesOrder());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Pelanggan\Repositories\Cache\CacheSalesOrderDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Pelanggan\Repositories\SurveyRepository',
            function () {
                $repository = new \Modules\Pelanggan\Repositories\Eloquent\EloquentSurveyRepository(new \Modules\Pelanggan\Entities\Survey());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Pelanggan\Repositories\Cache\CacheSurveyDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Pelanggan\Repositories\InstallationRepository',
            function () {
                $repository = new \Modules\Pelanggan\Repositories\Eloquent\EloquentInstallationRepository(new \Modules\Pelanggan\Entities\Installation());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Pelanggan\Repositories\Cache\CacheInstallationDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Pelanggan\Repositories\ActivationRepository',
            function () {
                $repository = new \Modules\Pelanggan\Repositories\Eloquent\EloquentActivationRepository(new \Modules\Pelanggan\Entities\Activation());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Pelanggan\Repositories\Cache\CacheActivationDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Pelanggan\Repositories\PelangganRepository',
            function () {
                $repository = new \Modules\Pelanggan\Repositories\Eloquent\EloquentPelangganRepository(new \Modules\Pelanggan\Entities\Pelanggan());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Pelanggan\Repositories\Cache\CachePelangganDecorator($repository);
            }
        );
// add bindings





    }
}
