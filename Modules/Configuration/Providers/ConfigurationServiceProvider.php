<?php

namespace Modules\Configuration\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Configuration\Events\Handlers\RegisterConfigurationSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;

class ConfigurationServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class,$this->getSidebarClassForModule('configuration', RegisterConfigurationSidebar::class));

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('configurations', array_dot(trans('configuration::configurations')));
            $event->load('bank', array_dot(trans('configuration::bank')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('configuration', 'permissions');

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
            'Modules\Configuration\Repositories\ConfigurationRepository',
            function () {
                $repository = new \Modules\Configuration\Repositories\Eloquent\EloquentConfigurationRepository(new \Modules\Configuration\Entities\Configuration());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Configuration\Repositories\Cache\CacheConfigurationDecorator($repository);
            }
        );
// add bindings

    }
}
