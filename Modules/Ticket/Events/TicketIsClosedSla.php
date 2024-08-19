<?php

namespace Modules\Ticket\Events;
use Illuminate\Http\Request;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\PaketBerlangganan;
use Illuminate\Support\Facades\Mail;
use Modules\Ticket\Emails\sla\TicketCloseSendMail;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Entities\TicketSla;
use Illuminate\Support\Facades\DB;

use Illuminate\Queue\SerializesModels;

class TicketIsClosedSla
{
    use SerializesModels;
    private $ticket_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticket_id)
    {
        $this->ticket_id = $ticket_id;
        $this->send_email();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    public function send_email()
    {
        //select table ticket support berdasarkan id ticket
        $ticket = DB::table('ticket_support')->where('ticket_support.ticket_id',$this->ticket_id)
                                             ->join('ticket_support_sla as b','b.ticket_id','=','ticket_support.ticket_id')
                                             ->first();

        //mengecek apakah di tiket support sla atau suspend ada tiket yang dicari apa tidak
      
        $data   = TicketSla::detail($this->ticket_id)->withMessage();
        $pelanggan  = Pelanggan::find($ticket->pelanggan_id)->withDataPresales();
        $paket      = PaketBerlangganan::find($pelanggan->paket_id);
        $pelanggan->paket_berlangganan = $paket->nama_paket." ( Rp. ".number_format($pelanggan->biaya_mrc,0,',','.').")";
        $company    = Company::find($pelanggan->osp_id);
        
        $send = [
            'ticket' => $data,
            'pelanggan' => $pelanggan,
            'company' => $company
        ];
        $data = [
            "title" => trans('ticket::tickets.title.tickets'),
            "data" => $send,
            "url" => route('admin.ticket.sla.session',['id' => $this->ticket_id]),
        ];
        
        // dd($data);
        $company = new Company;
        $admin_mail = $company->admin_email($pelanggan->osp_id);
        Mail::to($admin_mail)->send(new TicketCloseSendMail($data));
    }
}