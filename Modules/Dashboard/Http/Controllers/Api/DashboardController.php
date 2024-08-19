<?php

namespace Modules\Dashboard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Dashboard\Composers\WidgetViewComposer;
use Modules\Dashboard\Repositories\WidgetRepository;
use Modules\User\Contracts\Authentication;
use Nwidart\Modules\Contracts\RepositoryInterface;

class DashboardController extends AdminBaseController
{
    /**
     * @var WidgetRepository
     */
    private $widget;
    /**
     * @var Authentication
     */
    private $auth;

    /**
     * @param RepositoryInterface $modules
     * @param WidgetRepository $widget
     * @param Authentication $auth
     */
    public function __construct(RepositoryInterface $modules, WidgetRepository $widget, Authentication $auth)
    {
        parent::__construct();
        $this->widget = $widget;
        $this->auth = $auth;

        $this->middleware(function ($request, $next) use ($modules) {
            $this->bootWidgets($modules);

            return $next($request);
        });
    }


    /**
     * Boot widgets for all enabled modules
     * @param RepositoryInterface $modules
     */
    private function bootWidgets(RepositoryInterface $modules)
    {
        foreach ($modules->enabled() as $module) {
            
            if ($module->widgets) {
                foreach ($module->widgets as $widgetClass) {
                    app($widgetClass)->boot();
                }
            }
        }
    }


    public function widget() {
        $widgets = app(WidgetViewComposer::class);
        $data = $widgets->getSubViews();
        usort($data, function ($a, $b) {
            return $a['options']['x'] <=> $b['options']['x'];
        });
        return $data;
    }
}