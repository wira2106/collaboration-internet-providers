<?php

namespace Modules\Ticket\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use DateTime;
use Auth;

class ListTicketSupportSla extends Resource
{
    public function toArray($request)
    {
        $lama_mati = "";
        if($this->end_gangguan != null){
            $a = new DateTime($this->start_gangguan);
            $b = new DateTime($this->end_gangguan);
            $diff =  ($b->getTimestamp() - $a->getTimestamp())/60;
            $jam = floor($diff/60);
            $menit = $diff%60;
            
            if($jam != 0){
                $lama_mati .= $jam." Jam ";
            }
            if($menit != 0){
                $lama_mati .= $menit." Menit";
            }

        }else{
            $lama_mati = "-";
        }
        return [
            "ticket_id" => $this->ticket_id,
            "code" => $this->code,
            "pelanggan_id" => $this->pelanggan_id,
            "start_gangguan" => $this->start_gangguan,
            "end_gangguan" => $this->end_gangguan,
            "lama_mati" => $lama_mati,
            "accept_osp_by" => $this->accept_osp_by,
            "created_at" => $this->created_at,
            "closed_at" => $this->closed_at,
            "closed_by" => $this->closed_by,
            "accept_isp_by" => $this->accept_isp_by,
            "subject" => $this->subject,
            "status" => $this->status,
            "nama_pelanggan" => $this->nama_pelanggan,
            "site_id" => $this->site_id,
            "nama_isp" => $this->nama_isp,
            "nama_osp" => $this->nama_osp,
            "nama_wilayah" => $this->nama_wilayah,
            "urls" => [
                "edit_url" => Auth::user()->hasAccess('ticket.tickets.edit') ? route("admin.ticket.sla.session",['id' => $this->ticket_id]):null,
                'delete_url' =>  Auth::user()->hasAccess('ticket.tickets.destroy') ? route('admin.api.ticket.destroy',['id' => $this->ticket_id]):null,
            ]
        ];
    }
}