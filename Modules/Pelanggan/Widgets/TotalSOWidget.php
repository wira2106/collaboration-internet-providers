<?php

namespace Modules\Pelanggan\Widgets;

use Illuminate\Support\Facades\Auth;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;
use Modules\Pelanggan\Entities\Pelanggan;

class TotalSOWidget extends BaseWidget
{
    

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'TotalSOWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'pelanggan::widgets.total_so';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        $total = Pelanggan::totalPelangganByStatus('so');
        
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
        $user = Auth::user();
        // openaccess [1]
        // admin osp [2]
        // admin isp [3]
        return $user->hasRoleName('Super Admin') || $user->hasRoleName('Admin OSP') || $user->hasRoleName('Admin ISP') || $user->hasRoleName('NOC') || $user->hasRoleName('Teknisi');
    }
}
