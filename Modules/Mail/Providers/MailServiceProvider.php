<?php

namespace Modules\Mail\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Mail\Events\Handlers\RegisterMailSidebar;

class MailServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterMailSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('mails', array_dot(trans('mail::mails')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('mail', 'permissions');

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
            'Modules\Mail\Repositories\MailRepository',
            function () {
                $repository = new \Modules\Mail\Repositories\Eloquent\EloquentMailRepository(new \Modules\Mail\Entities\Mail());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Mail\Repositories\Cache\CacheMailDecorator($repository);
            }
        );
// add bindings

    }
}
