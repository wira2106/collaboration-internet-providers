<?php

namespace Modules\Company\Widgets;

use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;
use Modules\Pelanggan\Entities\Pelanggan;

class TotalTagihanBulanDepanWidget extends BaseWidget
{
    
    use ConvertToCurrency;
    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'TotalTagihanBulanDepanWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'company::widgets.total_tagihan_bulanan';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        $total = Pelanggan::totalTagihanBulanan();
        
        return ['total' => $this->rupiah($total)];
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
            'x' => '5',
            'hasAccess' => $this->hasAccess()
        ];
    }

    private function hasAccess() {
        return Auth::user()->hasRoleName('Admin ISP');
    }
}
