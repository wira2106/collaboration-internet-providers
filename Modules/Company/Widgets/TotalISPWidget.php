<?php

namespace Modules\Company\Widgets;

use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;

class TotalISPWidget extends BaseWidget
{
    

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'TotalISPWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'company::widgets.total_isp';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        $total = count((new Company())->getAllISP(Auth::user()));
        
        return ['total' => $total];
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
            'x' => '6',
            'hasAccess' => $this->hasAccess()
        ];
    }

    private function hasAccess() {
        // openaccess [1]
        return Auth::user()->hasRoleId(1);
    }
}
