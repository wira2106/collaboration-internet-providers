<?php

namespace Modules\Ticket\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TicketMessagesTransformer extends Resource
{
    public function toArray($request)
    {
        $attatchment = [];
        $att = json_decode($this->attachments);
        foreach ($att as $key => $value) {
            $attatchment[] = url('/uploadgambar/'.$value);
        }
        return [
            "message_id" => $this->message_id,
            "ticket_id" => $this->ticket_id,
            "osp_id" => $this->osp_id,
            "isp_id" => $this->isp_id,
            "message" => $this->message,
            "unsend" => $this->unsend,
            "created_at" => date('d M Y - H:i', strtotime($this->created_at)),
            "created_by" => $this->created_by,
            "attachments" => $attatchment,
            "osp_name" => $this->osp_name,
            "isp_name" => $this->isp_name,
            "nama_user" => $this->nama_user,
            "foto_profile" => ($this->foto_profile != null?url("/uploadgambar/".$this->foto_profile):url('/uploadgambar/nologo.png'))
        ];
    }
}
