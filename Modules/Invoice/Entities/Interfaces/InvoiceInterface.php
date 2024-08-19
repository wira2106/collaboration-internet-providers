<?php

namespace Modules\Invoice\Entities\Interfaces;

use Modules\Invoice\Entities\Invoice;

interface InvoiceInterface {

    /**
     * Buat Invoice Pelanggan
     * @param  int $isp_id
     * @param  int $osp_id
     * @param  int $request_wilayah_id
     * @param  DATE $periode
     * @param  string $invoice_no
     * @param  string $status ('pending', 'settlement') 
     * @param  int $ppn (%)
     * @return Invoice
     */

    public static function create_invoice_pelanggan($isp_id, $osp_id, $request_wilayah_id, $periode, $invoice_no, $status, $ppn);

}