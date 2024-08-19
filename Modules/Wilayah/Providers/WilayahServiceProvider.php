<?php

namespace Modules\Wilayah\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Wilayah\Events\Handlers\RegisterWilayahSidebar;

class WilayahServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class,$this->getSidebarClassForModule('wilayah', RegisterWilayahSidebar::class) );

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load("wilayahs", array_dot(trans("wilayah::wilayahs")));
            $event->load("pengajuans", array_dot(trans("wilayah::pengajuans")));
        });
    }

    public function boot()
    {
        $this->publishConfig('wilayah', 'permissions');

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
            'Modules\Wilayah\Repositories\WilayahRepository',
            function () {
                $repository = new \Modules\Wilayah\Repositories\Eloquent\EloquentWilayahRepository(new \Modules\Wilayah\Entities\Wilayah());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Wilayah\Repositories\Cache\CacheWilayahDecorator($repository);
            }
        );
// add bindings

    }
}
