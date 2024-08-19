<?php

namespace Modules\Company\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Company\Entities\BiayaKabel;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Company\Events\Handlers\RegisterCompanySidebar;
use Modules\Company\Repositories\Cache\CacheBiayaKabelRepository;
use Modules\Company\Repositories\Eloquent\EloquentBiayaKabelRepository;

class CompanyServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterCompanySidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('companies', array_dot(trans('company::companies')));
            // $event->load('staff', array_dot(trans('company::staff')));
            $event->load('paketberlangganans', array_dot(trans('company::paketberlangganans')));
            $event->load('rating', array_dot(trans('company::rating')));
            $event->load('biaya_kabel', array_dot(trans('company::biaya_kabel')));
            $event->load('slot_instalasi', array_dot(trans('company::slot_instalasi')));
            $event->load('dayoff', array_dot(trans('company::dayoff')));
            $event->load('range_biaya_kabel', array_dot(trans('company::range_biaya_kabel')));
            $event->load('workday', array_dot(trans('company::workday')));
            $event->load('diskon_paket', array_dot(trans('company::diskon_paket')));
            $event->load('rekening', array_dot(trans('company::rekening')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('company', 'permissions');

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
            'Modules\Company\Repositories\CompanyRepository',
            function () {
                $repository = new \Modules\Company\Repositories\Eloquent\EloquentCompanyRepository(new \Modules\Company\Entities\Company());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Company\Repositories\Cache\CacheCompanyDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Company\Repositories\StaffRepository',
            function () {
                $repository = new \Modules\Company\Repositories\Eloquent\EloquentStaffRepository(new \Modules\Company\Entities\Staff());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Company\Repositories\Cache\CacheStaffDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Company\Repositories\PaketBerlanggananRepository',
            function () {
                $repository = new \Modules\Company\Repositories\Eloquent\EloquentPaketBerlanggananRepository(new \Modules\Company\Entities\PaketBerlangganan());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Company\Repositories\Cache\CachePaketBerlanggananDecorator($repository);
            }
        );

        $this->app->bind(
            'Modules\Company\Repositories\BiayaKabelRepository',
            function() {
                $repository = new EloquentBiayaKabelRepository(new BiayaKabel());

                if(! config('app.cache')) {
                    return $repository;
                }

                return new CacheBiayaKabelRepository($repository);
            }
        );  



    }
}
