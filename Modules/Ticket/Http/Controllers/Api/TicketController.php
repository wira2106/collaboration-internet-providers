<?php

namespace Modules\Ticket\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Entities\TicketMessage;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\Ticket\Http\Requests\CreateTicketRequest;
use Modules\Ticket\Http\Requests\UpdateTicketRequest;
use Modules\Ticket\Repositories\TicketRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Ticket\Transformers\TicketSupportTransformer;
use Modules\Ticket\Transformers\TicketMessagesTransformer;
use Modules\Ticket\Transformers\ListTicketSupport;
use Modules\Ticket\Events\TicketIsCreated;
use Modules\Ticket\Events\TicketIsClosed;
use Modules\Ticket\Events\MessagesIsCreated;
use Auth;

class TicketController extends AdminBaseController
{
    public function data(Request $request)
    {
        $company  = new Company;
        $company_id = $request->get("company_id");

        if (!Auth::User()->hasRoleName('Super Admin')) {
            $comp = Auth::User()->company();
            $company_id = $comp->company_id;
        }

        $data = $this->serverPaginationFilteringFor($company_id, $request);
        $allDataCompany = [
            'companies' => $company->getAllCompany(),
            "selected" => $company_id
        ];

        return ListTicketSupport::collection($data)->additional($allDataCompany);
    }

    public function serverPaginationFilteringFor($company_id, Request $request)
    {
        $select = [
            'ticket_support.*',
            'a.nama_pelanggan',
            'b.site_id',
            'c.name as nama_isp',
            'd.name as nama_osp',
            'e.name as nama_wilayah',
        ];
        $data = Ticket::select($select)
            ->join('pelanggan as a', 'a.pelanggan_id', '=', 'ticket_support.pelanggan_id')
            ->join('presales as b', 'b.presale_id', '=', 'a.presale_id')
            ->join('companies as c', 'c.company_id', '=', 'a.isp_id')
            ->join('companies as d', 'd.company_id', '=', 'a.osp_id')
            ->join('wilayah as e', 'e.wilayah_id', '=', 'a.wilayah_id');

        if ($company_id != null && $company_id != "") {
            $company = Company::find($company_id);
            if ($company->type == 'osp') {
                $data = $data->where('a.osp_id', $company->company_id);
            } else {
                $data = $data->where('a.isp_id', $company->company_id);
            }
        }

        if ($request->status != null && $request->status != "") {
            $data = $data->where('ticket_support.status', $request->status);
        }


        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $data->where(function ($query) use ($term) {
                $query->orWhere('a.nama_pelanggan', 'LIKE', "%{$term}%")
                    ->orWhere('b.site_id', 'LIKE', "%{$term}%")
                    ->orWhere('c.name', 'LIKE', "%{$term}%")
                    ->orWhere('d.name', 'LIKE', "%{$term}%")
                    ->orWhere('e.name', 'LIKE', "%{$term}%")
                    ->orWhere('ticket_support.code', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';
            $data->orderBy($request->get('order_by'), $order);
        } else {
            $data->orderBy('ticket_support.code', 'desc');
        }

        return $data->paginate($request->get('per_page', 10));
    }


    public function create(Request $req)
    {
        $pelanggan_id = $req->pelanggan;
        $pelanggan = Pelanggan::find($pelanggan_id);
        $start_gangguan = $req->start_gangguan;
        $end_gangguan = $req->end_gangguan;
        $messages = $req->messages;
        $attachments = $req->attachments;

        // generate code
        $urutan = Ticket::where('created_at', 'like', '%' . date('Y-m') . '%')->count();
        $urutan = $urutan + 1;
        $code = date('y') . date('m') . $urutan;

        // tambah tiket support
        $insert = [
            'subject' => 'Gangguan internet pelanggan - ' . $pelanggan->nama_pelanggan,
            'code' => $code,
            'pelanggan_id' => $pelanggan_id,
            'start_gangguan' => $start_gangguan,
            'end_gangguan' => $end_gangguan,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::User()->id
        ];
        if ($end_gangguan != null) {
            $insert["accept_isp_by"] = Auth::User()->id;
            $insert["tgl_acc_isp"] = date('Y-m-d H:i:s');
        }
        $ticket_id = Ticket::insertGetId($insert);

        // upload gambar attachments
        $filename = [];
        foreach ($attachments as $key => $file) {
            $filename[] = ImageController::uploadFoto($file);
        }

        $filename = json_encode($filename);

        // masukkan ke message ticket support
        $company_user = Auth::User()->company();
        if ($company_user->type == 'isp') {
            $isp_id = $company_user->company_id;
            $osp_id = null;
        } else {
            $isp_id = null;
            $osp_id = $company_user->company_id;
        }
        TicketMessage::create([
            'ticket_id' => $ticket_id,
            'osp_id' => $osp_id,
            'isp_id' => $isp_id,
            'message' => $messages,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::User()->id,
            'attachments' => $filename
        ]);

        // create Log
        Auth::user()->createLog(trans('tickets::logs.create ticket.tipe'), trans('tickets::logs.create tickets.nama_pelanggan', ['nama_pelanggan' => $pelanggan->nama_pelanggan]));
        // send mail
        event($event = new TicketIsCreated($ticket_id));
        // dd('asdasd');
        return response()->json([
            'errors' => false,
            'message' => trans('ticket::tickets.messages.ticket created'),
        ]);
    }

    public function detailSession($id, Request $req)
    {
        $data = Ticket::detail($id)->withMessage();
        $pelanggan = Pelanggan::find($data->pelanggan_id);
        if (Auth::User()->getRoleName() != "Super Admin") {
            $access = [$pelanggan->isp_id, $pelanggan->osp_id];
            if (!in_array(Auth::User()->company()->company_id, $access)) {
                abort(Response::HTTP_FORBIDDEN, "Forbidden");
            }
        }

        return response()->json(new TicketSupportTransformer($data));
    }

    public function getMessages($ticket_id, Request $req)
    {
        $id_terahir = $req->id_terakhir;
        $data = TicketMessage::getMessages($ticket_id, $id_terahir);

        return response()->json(TicketMessagesTransformer::collection($data));
    }

    public function sendMessages($ticket_id, Request $req)
    {
        $company_user = Auth::User()->company();
        $ticket = Ticket::detail($req->ticket_id);
        if ($company_user->type == 'isp') {
            $isp_id = $company_user->company_id;
            $osp_id = null;
            $ticket->status = "reply_isp";
        } else if ($company_user->type == 'osp') {
            $isp_id = null;
            $osp_id = $company_user->company_id;
            $ticket->status = "reply_osp";
        } else {
            $isp_id = null;
            $osp_id = null;
            $ticket->status = "reply_admin";
        }
        $ticket->save();

        $filename = [];
        if ($req->file('attachments') != null) {
            foreach ($req->file('attachments') as $key => $file) {
                $filename[] = ImageController::uploadFoto($file);
            }
        }

        $filename = json_encode($filename);

        $id = TicketMessage::insertGetId([
            'ticket_id' => $ticket_id,
            'osp_id' => $osp_id,
            'isp_id' => $isp_id,
            'message' => $req->messages,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::User()->id,
            'attachments' => $filename
        ]);

        event($event = new MessagesIsCreated($id, $ticket_id));

        return response()->json([
            'errors' => false,
            'message' => trans('ticket::tickets.messages.ticket created'),
        ]);
    }

    public function updateTimeSla(Request $req)
    {
        $data = Ticket::detail($req->ticket_id);
        $data->start_gangguan = $req->start_gangguan;
        $data->end_gangguan = $req->end_gangguan;
        if (Auth::User()->company()->type == 'osp') {
            $data->accept_osp_by = Auth::User()->id;
            $data->tgl_acc_osp = date('Y-m-d H:i:s');
            $data->accept_isp_by = null;
            $data->tgl_acc_isp = null;
        } else {
            $data->accept_isp_by = Auth::User()->id;
            $data->tgl_acc_isp = date('Y-m-d H:i:s');
            $data->accept_osp_by = null;
            $data->tgl_acc_osp = null;
        }
        $data->save();

        return response()->json([
            'errors' => false,
            'message' => trans('ticket::tickets.messages.tanggal updated'),
        ]);
    }

    public function approveTimeSla($ticket_id)
    {
        $data = Ticket::detail($ticket_id);
        if (Auth::User()->company()->type == 'osp') {
            $data->accept_osp_by = Auth::User()->id;
            $data->tgl_acc_osp = date('Y-m-d H:i:s');
        } else {
            $data->accept_isp_by = Auth::User()->id;
            $data->tgl_acc_isp = date('Y-m-d H:i:s');
        }
        $data->save();
        $ticket = Ticket::detail($ticket_id);

        return response()->json([
            'errors' => false,
            'message' => trans('ticket::tickets.messages.tanggal approve'),
            'data' => $ticket
        ]);
    }

    public function closeTicket($ticket_id)
    {
        $data = Ticket::detail($ticket_id);
        $data->closed_by = Auth::User()->id;
        $data->closed_at = date('Y-m-d H:i:s');
        $data->status = 'closed';
        $data->save();

        // send mail close ticket support
        // event($event = new TicketIsClosed($ticket_id));

        return response()->json([
            'errors' => false,
            'message' => trans('ticket::tickets.messages.ticket closed'),
        ]);
    }

    public function destroy($ticket_id)
    {
        $data = Ticket::find($ticket_id);
        $data->deleted_at = date('Y-m-d H:i:s');
        $data->deleted_by = Auth::User()->id;
        $data->save();

        return response()->json([
            'errors' => false,
            'message' => trans('ticket::tickets.messages.ticket deleted'),
        ]);
    }
}
