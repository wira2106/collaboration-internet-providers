<?php

namespace Modules\Ticket\Events;
use Illuminate\Http\Request;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\PaketBerlangganan;
use Illuminate\Support\Facades\Mail;
use Modules\Ticket\Emails\TicketSendMail;
use Modules\Ticket\Emails\suspend\MessagesSendMail;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Entities\TicketSuspend;
use Modules\Ticket\Entities\TicketMessage;

use Illuminate\Queue\SerializesModels;

class MessagesIsCreatedSuspend
{
    use SerializesModels;
    private $ticket_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($messages_id, $ticket_id)
    {
        $this->ticket_id = $ticket_id;
        $this->messages_id = $messages_id;
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
        $data       = TicketSuspend::detail($this->ticket_id);
        $messages   = TicketMessage::find($this->messages_id);
        $pelanggan  = Pelanggan::find($data->pelanggan_id)->withDataPresales();
        $paket      = PaketBerlangganan::find($pelanggan->paket_id);
        $pelanggan->paket_berlangganan = $paket->nama_paket." ( Rp. ".number_format($pelanggan->biaya_mrc,0,',','.').")";
        $company_id = ($messages->isp_id != null?$messages->isp_id:$messages->osp_id);
        $company    = Company::find($company_id);
        
        $send = [
            'ticket' => $data,
            'messages' => $messages,
            'pelanggan' => $pelanggan,
            'company' => $company
        ];

        $data = [
            "title" => trans('ticket::tickets.title.tickets'),
            "data" => $send,
            "url" => route('admin.ticket.sla.session',['id' => $this->ticket_id]),
        ];
        
        $company = new Company;
        $admin_mail = $company->admin_email($company_id);
        Mail::to($admin_mail)->send(new MessagesSendMail($data));
    }
}