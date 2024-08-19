<?php

namespace Modules\Analytic\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Analytic\Events\Handlers\RegisterAnalyticSidebar;

class AnalyticServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterAnalyticSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('analytics', array_dot(trans('analytic::analytics')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('analytic', 'permissions');

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
            'Modules\Analytic\Repositories\AnalyticRepository',
            function () {
                $repository = new \Modules\Analytic\Repositories\Eloquent\EloquentAnalyticRepository(new \Modules\Analytic\Entities\Analytic());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Analytic\Repositories\Cache\CacheAnalyticDecorator($repository);
            }
        );
// add bindings

    }
}
