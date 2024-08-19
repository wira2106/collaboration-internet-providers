<?php

namespace Modules\Invoice\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class InvoiceTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'invoice_id' => $this->invoice_id,
            'invoice_no' => $this->invoice_no,
            'periode' => date('M Y', strtotime($this->periode)),
            'status' => $this->status,
            'created_at' => date('d M Y H:i', strtotime($this->created_at)),
            'payment_at_day' => ($this->settlement_at != null ? date('d M Y', strtotime($this->settlement_at)) : null),
            'payment_at_time' => ($this->settlement_at != null ? date('H:i', strtotime($this->settlement_at)) : null),
            'due_date' => date('d/m/Y', strtotime($this->due_date)),
            'amount' => 'Rp. '.number_format($this->amount,0,',','.'),
            'ppn' => 'Rp. '.number_format($this->nominal_ppn,0,',','.'),
            'dpp' => 'Rp. '.number_format($this->nominal_dpp,0,',','.'),
            'title' => $this->title,
            'isp_id' => $this->isp_id,
            'osp_id' => $this->osp_id,
            'nama_osp' => $this->nama_osp,
            'nama_isp' => $this->nama_isp,
            'type' => $this->type,
            'jumlah_pending' => 0
        ];
    }
}