<?php

namespace Modules\Saldo\Widgets;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;

class SaldoWidget extends BaseWidget
{
    use ConvertToCurrency;
    

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'SaldoWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'saldo::admin.widgets.saldo';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        $saldo = Auth::user()->company()->saldo();
        $dibekukan = $saldo->dibekukan > 0 
                        ? $this->rupiah($saldo->dibekukan) 
                        : null;
        return [
            'saldo' => $this->rupiah($saldo->saldo),
            'dibekukan' => $dibekukan
        ];
    }

    /**
     * Get the widget type
     * @return string
     */
    protected function options()
    {
        return [
            'width' => '3',
            'height' => '3',
            'x' => '0',
            'hasAccess' => $this->hasAccess()
        ];
    }

    private function hasAccess() {
        $user = Auth::user();
        
        return $user->hasRoleName("Super Admin") || $user->hasRoleName("Admin OSP") || $user->hasRoleName("Admin ISP");
    }
}
