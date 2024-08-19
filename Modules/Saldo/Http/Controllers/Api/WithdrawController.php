<?php

namespace Modules\Saldo\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Company;
use Modules\Saldo\Entities\withdraw;
use Modules\Saldo\Http\Requests\CreatewithdrawRequest;
use Modules\Saldo\Http\Requests\UpdatewithdrawRequest;
use Modules\Saldo\Repositories\withdrawRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Saldo\Events\WithdrawIsCancel;
use Modules\Saldo\Events\WithdrawIsConfirmed;
use Modules\Saldo\Events\WithdrawIsCreated;
use Modules\Saldo\Events\WithdrawIsUpdated;
use Modules\Saldo\Transformers\WithdrawTransformer;
use Modules\Utils\Http\Controllers\ImageController;

class WithdrawController extends AdminBaseController
{
    /**
     * @var withdrawRepository
     */
    private $withdraw;

    public function __construct(withdrawRepository $withdraw)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
        $this->withdraw = $withdraw;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $status = '';
        if ($request->type !== 'all') {
            $status = $request->type;
        }
        $withdraw = new withdraw();
        $company = new Company();
        $user = Auth::user()->company();
        $saldo = [
            'saldo' => number_format($company->getSaldo($user)->saldo),
            'max_withdraw' => $company->getSaldo($user)->saldo
        ];

        return WithdrawTransformer::collection($withdraw->historyWithdraw($request, $status))
            ->additional($saldo);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('saldo::admin.withdraws.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatewithdrawRequest $request
     * @return Response
     */
    public function store(CreatewithdrawRequest $request)
    {
        DB::beginTransaction();
        $company_model = new Company;
        $status      = "pending";
        $company     = Auth::user()->company();
        $company_id  = $company->company_id;
        $getSaldo    = $company_model->getSaldo($company);
        $amount      = str_replace(',', '', $request->amount);

        // cari saldo akhir dan update saldo
        $saldoAkhir  = $getSaldo->saldo - $amount;
        if ($saldoAkhir < 0) {
            return response()->json([
                'errors' => true,
                'message' => "error",
            ]);
        }
        $updateSaldo = [
            'saldo' => $saldoAkhir
        ];
        DB::table('saldo')->where('saldo_id', '=', $getSaldo->saldo_id)->update($updateSaldo);
        // tambah mutasi
        $mutasi = [
            'saldo_id' => $getSaldo->saldo_id,
            'nominal' => $amount,
            'tipe' => 'credit',
            'created_at' => date('Y-m-d H:i:s'),
            'deskripsi' => trans('saldo::withdraws.insert.deskripsi',
            [
                'nominal'=>number_format($amount, 0, ',', '.'), 
                'fullname'=> Auth::User()->full_name
            ])
            // 'Request penarikan Saldo Sebesar Rp.' . number_format($amount, 0, ',', '.') . ' oleh ' . Auth::User()->full_name
        ];
        DB::table('mutasi_saldo')->insert($mutasi);

        // tambah tarik saldo
        $data = [
            'company_id' => $company_id,
            'bank_account_id' => $request->bank_account_id,
            'amount' => $amount,
            'status' => $status,
            'created_at' => date('Y-m-d h:i:s'),
            'created_by' => Auth::user()->id
        ];
        DB::table('tarik_saldo')->insert($data);

        event(new WithdrawIsCreated($company, $data));

        DB::commit();

        return response()->json([
            'errors' => false,
            'message' => trans('saldo::saldos.messages.success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  withdraw $withdraw
     * @return Response
     */
    public function edit(withdraw $withdraw)
    {
        return view('saldo::admin.withdraws.edit', compact('withdraw'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  withdraw $withdraw
     * @param  UpdatewithdrawRequest $request
     * @return Response
     */
    public function update(Request $request)
    {
        $company = new Company();
        $data_company = Company::find($request->company_id);
        $getSaldo = $company->getSaldo($data_company);
        $tarik_saldo = withdraw::where('tarik_saldo_id', $request->tarik_saldo_id)->first();
        $amount = $tarik_saldo->amount;
        $keterangan = '';

        if ($request->status !== 'cancel') {
            $filename = ImageController::uploadFoto($request->file_bukti_transfer);
            $data_tarik_saldo = [
                'status' => $request->status,
                'success_on' => date('Y-m-d h:i:s'),
                'success_by' => Auth::user()->id,
                'bukti_transfer' => $filename,
            ];

            event(new WithdrawIsUpdated($data_company, $tarik_saldo));
        } else {
            // tambah mutasi debit
            
            $keterangan = $request->keterangan;
            $mutasi = [
                'saldo_id' => $getSaldo->saldo_id,
                'nominal' => $amount,
                'tipe' => 'debit',
                'created_at' => date('Y-m-d H:i:s'),
                'deskripsi' => trans('saldo::withdraws.insert.deskripsi mutasi debit',[
                                                                                        'nominal'=>number_format($amount, 0, ',', '.'),
                                                                                        'keterangan'=>$keterangan
                                                                                        ])

                // Penolakan tarik Saldo Sebesar Rp.' . number_format($amount, 0, ',', '.') . ' oleh Admin karena ' . $keterangan
            ];
            DB::table('mutasi_saldo')->insert($mutasi);
            // update saldo
            $saldoAkhir = $getSaldo->saldo + $amount;
            $updateSaldo = [
                'saldo' => $saldoAkhir
            ];
            DB::table('saldo')->where('saldo_id', '=', $getSaldo->saldo_id)->update($updateSaldo);
            // update data tarik saldo
            $data_tarik_saldo = [
                'status' => $request->status,
                'keterangan' => $keterangan,
                'success_on' => date('Y-m-d h:i:s'),
                'success_by' => Auth::user()->id,
                'bukti_transfer' => null,
            ];
            
            event(new WithdrawIsCancel($data_company, $tarik_saldo));
        }

        DB::table('tarik_saldo')->where('tarik_saldo_id', '=', $request->tarik_saldo_id)
            ->update($data_tarik_saldo);

        event(new WithdrawIsConfirmed($data_company, $tarik_saldo, $data_tarik_saldo));
        return response()->json([
            'errors' => false,
            'message' => trans('saldo::saldos.messages.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  withdraw $withdraw
     * @return Response
     */
    public function destroy(withdraw $withdraw)
    {
        $this->withdraw->destroy($withdraw);

        return redirect()->route('admin.saldo.withdraw.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('saldo::withdraws.title.withdraws')]));
    }

    public function getRekening()
    {
        $rekening = new withdraw();
        $rekeningCompany = $rekening->getRekening();
        return compact('rekeningCompany');
    }
}