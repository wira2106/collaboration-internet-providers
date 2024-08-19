<?php

namespace Modules\Pelanggan\Widgets;

use Illuminate\Support\Facades\Auth;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;
use Modules\Pelanggan\Entities\Pelanggan;

class TotalAktivasiWidget extends BaseWidget
{
    

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'TotalAktivasiWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'pelanggan::widgets.total_aktivasi';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        $total = Pelanggan::totalPelangganByStatus('aktivasi');
        
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
            'x' => '9',
            'hasAccess' => $this->hasAccess()
        ];
    }

    public function hasAccess() {
        $user = Auth::user();
        return $user->hasRoleName('Super Admin') || $user->hasRoleName('Admin OSP') || $user->hasRoleName('Admin ISP') || $user->hasRoleName('NOC') || $user->hasRoleName('Teknisi');
    }
}
