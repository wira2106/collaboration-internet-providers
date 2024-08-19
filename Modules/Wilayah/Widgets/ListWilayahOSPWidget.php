<?php

namespace Modules\Wilayah\Widgets;

use Illuminate\Support\Facades\Auth;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;
use Modules\Wilayah\Entities\Wilayah;

class ListWilayahOSPWidget extends BaseWidget
{
    

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'ListWilayahOSPWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'wilayah::widgets.list_wilayah';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        return [];
    }

    /**
     * Get the widget type
     * @return string
     */
    protected function options()
    {
        return [
            'width' => '2',
            'height' => '0',
            'x' => '21',
            'hasAccess' => $this->hasAccess()
        ];
    }

    private function hasAccess() {
        // openaccess [1]
        return Auth::user()->hasRoleId(1);
    }
}
