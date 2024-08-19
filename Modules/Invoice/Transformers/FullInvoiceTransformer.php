<?php

namespace Modules\Invoice\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID.utf8');
class FullInvoiceTransformer extends Resource
{
    public function toArray($request)
    {
        $created_at = date_create(date('Y-m-d',  strtotime($this->created_at)));
        

        return [
            'invoice_id' => $this->invoice_id,
            'invoice_no' => $this->invoice_no,
            'periode' => $this->periode,
            'status' => $this->status,
            'created_at' => date('Y-m-d', strtotime($this->created_at)),
            'settlement_at' => $this->settlement_at,
            'from' => $this->from,
            'to' => $this->to,
            'item' => $this->item,
            'ppn' => $this->ppn,
            'nama_tagihan' => trans('invoice::invoices.tagihan bulanan pelanggan', [
                'bulan' =>  strftime('%B %Y', $created_at->getTimestamp() )
            ]),
            'jatuh_tempo' => $this->due_date,
            'total_tagihan' => $this->hitung_total_tagihan(),
            'type' => $this->type

        ];
    }

    private function hitung_total_tagihan() {
        $total = 0;
        foreach($this['item'] as $key => $value) {
            $total += $value['amount'];
        }
        $ppn = $total * ($this->ppn / 100);

        return $total + $ppn;
    }
}