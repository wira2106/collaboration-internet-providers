<?php

namespace Modules\Ticket\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TicketSupportSlaTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'ticket_id' => $this->ticket_id,
            'code' => $this->code,
            'pelanggan_id' => $this->pelanggan_id,
            'start_gangguan' => $this->start_gangguan,
            'end_gangguan' => $this->end_gangguan,
            'accept_osp_by' => $this->accept_osp_by,
            'accept_isp_by' => $this->accept_isp_by,
            'subject' => $this->subject,
            'status' => $this->status,
            'messages' => $this->messages,
            'created_at' => date('Y-m-d h:i:s', strtotime($this->created_at)),
            'user_approve_isp' => ($this->accept_isp_by != null?$this->user_approve_isp." (".date('d/M/Y - H:i', strtotime($this->tgl_acc_isp)).")":'-'),
            'user_approve_osp' => ($this->accept_osp_by != null?$this->user_approve_osp." (".date('d/M/Y - H:i', strtotime($this->tgl_acc_osp)).")":'-')
        ];
    }
}