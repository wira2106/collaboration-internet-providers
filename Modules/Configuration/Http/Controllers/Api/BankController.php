<?php

namespace Modules\Configuration\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Configuration\Entities\Configuration;
use Modules\Configuration\Entities\Bank;
use Modules\Configuration\Http\Requests\FormBankRequest;
use Modules\Configuration\Repositories\ConfigurationRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Configuration\Transformers\BankTransformer;
use Modules\Utils\Http\Controllers\ImageController;
use DB;
use Illuminate\Support\Facades\Auth;

class BankController extends AdminBaseController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }
    public function listBank()
    {
        $bank = new Bank;
        $data = $bank->getBank();
        return BankTransformer::collection($data);
    }

    public function pagination(Request $request)
    {
        $data = $this->serverPaginationFilteringFor($request);
        return BankTransformer::collection($data);
    }

    public function serverPaginationFilteringFor(Request $request)
    {
        $staff      = DB::table("bank")->select(["bank.*"])
            ->where("deleted_at", "=", null);

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $staff->where('namaBank', 'LIKE', "%{$term}%");
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $staff->orderBy($request->get('order_by'), $order);
        } else {
            $staff->orderBy('namaBank', 'asc');
        }

        return $staff->paginate($request->get('per_page', 10));
    }

    public function submit(FormBankRequest $req)
    {
        $form = [
            "namaBank" => $req->name
        ];
        if ($req->bank_id == null) {
            $form["created_at"] = date('Y-m-d H:i:s');
            $form["created_by"] = Auth::User()->id;
            $this->log->createLog(trans('user::logs.tipe.create'), trans('configuration::bank.log.create', ['data' => $req->name]));
        } else {
            $form["updated_at"] = date('Y-m-d H:i:s');
            $form["updated_by"] = Auth::User()->id;
            $this->log->createLog(trans('user::logs.tipe.update'), trans('configuration::bank.log.update', ['data' => $req->name]));
        }

        if ($req->file("logo") != null) {
            $form['logo'] = ImageController::uploadFoto($req->file("logo"));
        }

        $bankAccount = Bank::updateOrCreate(['bank_id' => $req->bank_id], $form);

        $message = trans('core::core.messages.resource created', ['name' => trans('configuration::bank.title.bank')]);
        if ($req->bank_id != null) {
            $message = trans('core::core.messages.resource updated', ['name' => trans('configuration::bank.title.bank')]);
        }
        return Response()->json([
            'errors' => false,
            'message' => $message
        ]);
    }

    public function destroy($id)
    {
        $data = Bank::find($id);
        $data->delete();
        $this->log->createLog(trans('user::logs.tipe.delete'), trans('configuration::bank.log.delete', ['data' => $data->namaBank]));
        return Response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource deleted', ['name' => trans('configuration::bank.title.bank')])
        ]);
    }

    public function find($id)
    {
        $data = Bank::find($id);

        return new BankTransformer($data);
    }
}
