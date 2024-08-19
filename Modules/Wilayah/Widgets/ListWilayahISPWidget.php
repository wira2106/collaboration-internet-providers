<?php

namespace Modules\Wilayah\Widgets;

use Illuminate\Support\Facades\Auth;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;
use Modules\Wilayah\Entities\Wilayah;

class ListWilayahISPWidget extends BaseWidget
{
    

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'ListWilayahISPWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'wilayah::widgets.list_wilayah_isp';
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
            'x' => '23',
            'hasAccess' => $this->hasAccess()
        ];
    }

    private function hasAccess() {
        // openaccess [1]
        return Auth::user()->hasRoleName('Admin ISP');
    }
}
