<?php

namespace Modules\Company\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\BankAccount;
use Modules\Company\Events\RekeningIsCreated;
use Modules\Company\Events\RekeningIsCreating;
use Modules\Company\Events\RekeningIsDestroy;
use Modules\Company\Events\RekeningIsUpdated;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Company\Transformers\ListRekeningTransformer;


class RekeningController extends AdminBaseController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
    }
    public function pagination(Request $request)
    {
        $company_id = $request->get("company_id");
        if ($company_id == null) {
            $company = Auth::user()->company();
        } else {
            $company = Company::where('company_id', $company_id)->first();
        }
        $data = $this->serverPaginationFilteringFor($company, $request);
        return ListRekeningTransformer::collection($data);
    }

    public function serverPaginationFilteringFor(Company $company, Request $request)
    {
        $bank_account = BankAccount::select(['bank_account.*', 'bank.namaBank'])
            ->join('bank', 'bank_account.bank_id', "=", 'bank.bank_id')
            ->where('company_id', $company->company_id);

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $bank_account->where(function ($query) use ($term) {
                $query->where('bank.namaBank', 'LIKE', "%{$term}%")
                    ->orWhere('atas_nama', 'LIKE', "%{$term}%")
                    ->orWhere('rekening', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $bank_account->orderBy($request->get('order_by'), $order);
        } else {
            $bank_account->orderBy('bank.namaBank', 'asc');
        }

        return $bank_account->paginate($request->get('per_page', 10));
    }

    public function destroy(BankAccount $rekening)
    {
        $rekening->delete();

        event(new RekeningIsDestroy($rekening));

        return Response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource deleted', ['name' => trans('company::companies.form.title.rekening')])
        ]);
    }

    public function submit(BankAccount $rekening, Request $req)
    {

        event($event = new RekeningIsCreating($rekening, $req->all()));

        if ($req->bank_account_id == null) {
            $event->setAttributes([
                'created_by' => Auth::user()->id
            ]);
        } else {
            $event->setAttributes([
                'updated_by' => Auth::user()->id
            ]);
        }

        $rekening->fill($event->getAttributes());
        

        if($req->bank_account_id) {
            event($event = new RekeningIsUpdated($rekening, $event->getAttributes()));
        } else {
            event($event = new RekeningIsCreated($rekening, $event->getAttributes()));
        }

        $rekening->save();


        $message = trans('core::core.messages.resource created', ['name' => trans('company::companies.form.title.rekening')]);
        if ($req->bank_account_id != null) {
            $message = trans('core::core.messages.resource updated', ['name' => trans('company::companies.form.title.rekening')]);
        }
        return Response()->json([
            'error' => false,
            'message' => $message
        ]);
    }
}
