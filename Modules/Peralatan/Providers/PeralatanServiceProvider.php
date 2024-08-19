<?php

namespace Modules\Peralatan\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Peralatan\Events\Handlers\RegisterPeralatanSidebar;

class PeralatanServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterPeralatanSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('alats', array_dot(trans('peralatan::alats')));
            $event->load('perangkats', array_dot(trans('peralatan::perangkats')));
            $event->load('barangs', array_dot(trans('peralatan::barangs')));
            $event->load('peralatans', array_dot(trans('peralatan::peralatans')));
            // append translations




        });
    }

    public function boot()
    {
        $this->publishConfig('peralatan', 'permissions');

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
            'Modules\Peralatan\Repositories\AlatRepository',
            function () {
                $repository = new \Modules\Peralatan\Repositories\Eloquent\EloquentAlatRepository(new \Modules\Peralatan\Entities\Alat());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Peralatan\Repositories\Cache\CacheAlatDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Peralatan\Repositories\PerangkatRepository',
            function () {
                $repository = new \Modules\Peralatan\Repositories\Eloquent\EloquentPerangkatRepository(new \Modules\Peralatan\Entities\Perangkat());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Peralatan\Repositories\Cache\CachePerangkatDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Peralatan\Repositories\BarangRepository',
            function () {
                $repository = new \Modules\Peralatan\Repositories\Eloquent\EloquentBarangRepository(new \Modules\Peralatan\Entities\Barang());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Peralatan\Repositories\Cache\CacheBarangDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Peralatan\Repositories\PeralatanRepository',
            function () {
                $repository = new \Modules\Peralatan\Repositories\Eloquent\EloquentPeralatanRepository(new \Modules\Peralatan\Entities\Peralatan());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Peralatan\Repositories\Cache\CachePeralatanDecorator($repository);
            }
        );
// add bindings




    }
}
