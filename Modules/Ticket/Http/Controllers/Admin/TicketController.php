<?php

namespace Modules\Ticket\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Http\Requests\CreateTicketRequest;
use Modules\Ticket\Http\Requests\UpdateTicketRequest;
use Modules\Ticket\Repositories\TicketRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class TicketController extends AdminBaseController
{
    public function index()
    {
        return view('ticket::admin.tickets.index');
    }

    public function create()
    {
        return view('ticket::admin.tickets.index');
    }

    public function session()
    {
        return view('ticket::admin.tickets.index');
    }
}
