<?php

namespace Modules\Saldo\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Saldo\Entities\topup;
use Modules\Company\Entities\Company;
use Modules\Configuration\Entities\Bank;
use Modules\Company\Entities\BankAccount;
use Modules\User\Entities\Sentinel\User;
use Modules\Saldo\Http\Requests\CreatetopupRequest;
use Modules\Saldo\Http\Requests\UpdatetopupRequest;
use Modules\Saldo\Repositories\topupRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Saldo\Events\TopupIsCancel;
use Modules\Saldo\Events\TopupIsConfirmed;
use Modules\Saldo\Events\TopupIsCreated;
use Modules\Saldo\Events\TopupIsUpdated;
use Modules\Saldo\Transformers\TopupTransformer;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Saldo\Entities\Saldo;

class TopupController extends AdminBaseController
{
    public $briva_url;
    public $briva_token;

    public function __construct()
    {
        $this->briva_token = config('services.briva_key');
        if(config('services.briva_production')){
            $this->briva_url = config('services.briva_url.production');
        }else{
            $this->briva_url = config('services.briva_url.sandbox');
        }
    }


    public function index(Request $request)
    {
        $status = '';
        if ($request->type !== 'all') {
            $status = $request->type;
        }
        $topup = new topup();
        $company = new Company();
        $user = Auth::user()->company();
        $saldo = [
            'saldo' => number_format($company->getSaldo($user)->saldo),
            'total_tagihan' => Pelanggan::totalTagihanBulanan()
        ];
        return TopupTransformer::collection($topup->historyTopup($request, $status))
            ->additional($saldo);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $filename = ImageController::uploadFoto($request->file_bukti_transfer);
        $status = "pending";
        $company_id = Auth::user()->company()->company_id;
        $amount = str_replace(',', '', $request->amount);
        $bank_id = $request->bank_id;
        $rekening_pengirim = $request->rekening_pengirim;
        if ($request->bank_id == null) {
            $bank_id = $request->rekening_pengirim['bank_id'];
            $rekening_pengirim = $request->rekening_pengirim['rekening'];
            // update bank
            $bank_account =  BankAccount::where('rekening', $rekening_pengirim)->where('company_id',$company_id)->first();
            $bank_account->atas_nama = $request->atas_nama."asd";
            $bank_account->updated_at = date('Y-m-d H:i:s');
            $bank_account->updated_by = Auth::user()->id;
            $bank_account->save();
        }else{
            // create new bank account to company
            BankAccount::create([
                'company_id' => $company_id,
                'bank_id' => $request->bank_id,
                'atas_nama' => $request->atas_nama,
                'rekening' => $rekening_pengirim,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ]);
        }
            
        $data = [
            'company_id' => (int)$company_id,
            'bank_id' => (int)$bank_id,
            'bank_account_id' => (int)$request->bank_account_id,
            'amount' => (int)$amount,
            'rekening_pengirim' => (int)$rekening_pengirim,
            'atas_nama' => $request->atas_nama,
            'bukti_transfer' => $filename,
            'status' => $status,
            'tgl_transfer' => $request->tgl_transfer,
            'keterangan' => $request->keterangan,
            'created_at' => date('Y-m-d h:i:s'),
            'created_by' => Auth::user()->id
        ];
        DB::table('topup_saldo')->insert($data);
        event(new TopupIsCreated($data));

        DB::commit();
        return response()->json([
            'errors' => false,
            'message' => trans('saldo::saldos.messages.success'),
        ]);
    }

    public function update(Request $request)
    {
        $company = new Company();
        $keterangan = '';
        $data_company = Company::find($request->company_id);
        $getSaldo = $company->getSaldo($data_company);
        $topup = topup::where('topup_id', $request->topup_id)->first();
        $data_topup = $topup;
        $amount = $topup->amount;
        $saldo     = $getSaldo->saldo + $amount;
        $updateSaldo = [
            'saldo' => $saldo
        ];

        $mutasi = [
            'saldo_id' => $getSaldo->saldo_id,
            'nominal' => $amount,
            'tipe' => 'debit',
            'created_at' => date('Y-m-d H:i:s'),
            'deskripsi' => 'Topup Saldo Sebesar Rp.' . number_format($amount, 0, ',', '.') . " oleh " . User::find($topup->created_by)->full_name
        ];

        if ($request->status !== 'cancel') {
            DB::table('saldo')
                ->where('saldo_id', '=', $getSaldo->saldo_id)->update($updateSaldo);
            DB::table('mutasi_saldo')->insert($mutasi);
            
            event(new TopupIsUpdated($data_company));
        } else {
            $keterangan = $request->keterangan;
            event(new TopupIsCancel($data_company, $data_topup));
        }

        $topup = [
            'status' => $request->status,
            'keterangan' => $keterangan,
            'success_on' => date('Y-m-d h:i:s'),
            'success_by' => Auth::user()->id,
        ];

        event(new TopupIsConfirmed($data_company, $data_topup,$topup));

        DB::table('topup_saldo')
            ->where('topup_id', '=', $request->topup_id)->update($topup);

        return response()->json([
            'errors' => false,
            'message' => trans('saldo::saldos.messages.success'),
        ]);
    }

    public function getRekening()
    {
        $rekening = new topup();
        $rekeningOpenaccess = $rekening->getRekeningOpenaccess();
        $rekeningPengirim = $rekening->getRekeningPengirim();
        $bank = $rekening->getBank();
        return compact('rekeningOpenaccess', 'rekeningPengirim', 'bank');
    }

    public function createBriva(Request $req)
    {
        $company = Auth::User()->company();
        $amount = $req->amount;
        
        $request_headers = array(
            "Authorization:Bearer ".$this->briva_token,
        );  
        
        $urlPost = $this->briva_url."/create";
        $payload = [
            'name' => $company->name,
            'amount' => $amount,
            'keterangan' => 'Top up Saldo'
        ];

        $chPost = curl_init();
        curl_setopt($chPost, CURLOPT_URL,$urlPost);
        curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
        curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
        $resultPost = curl_exec($chPost);
        $resultPost = json_decode($resultPost);
        curl_close($chPost);
    
        if(isset($resultPost->status)){
            if($resultPost->status){
                $data = [
                    'company_id' => (int)$company->company_id,
                    'amount' => (int)$amount,
                    'mode' => 'briva',
                    'status' => 'pending',
                    'created_at' => date('Y-m-d h:i:s'),
                    'created_by' => Auth::user()->id,
                    'brivaNo' => $resultPost->data->brivaNo,
                    'custCode' => $resultPost->data->custCode,
                    'expired_at' => $resultPost->data->expiredDate
                ];
                DB::table('topup_saldo')->insert($data);

                Auth::User()->createLog(
                    trans('saldo::topups.logs.create briva.tipe'), 
                    trans('saldo::topups.logs.create briva.description')
                );

                return response()->json([
                    'errors' => false,
                    'message' => trans('saldo::topups.messages.success')
                ]); 
            }else{
                return response()->json([
                    'errors' => true,
                    'message' => $resultPost->errDesc,
                ]);    
            }
        }else{
            return response()->json([
                'errors' => true,
                'message' => trans('saldo::saldos.messages.error'),
            ]);
        }
    }

    public function deleteBriva(Request $req)
    {
        $id = $req->id;
        $data = DB::table('topup_saldo')->where('topup_id', $id)->first();
        if($data != null){
            $request_headers = array(
                "Authorization:Bearer ".$this->briva_token,
            );  
            
            $urlPost = $this->briva_url."/delete";
            $payload = [
                'customercode' => $data->custCode,
            ];
    
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $resultPost = json_decode($resultPost);
            curl_close($chPost);

            if(isset($resultPost->status)){
                if($resultPost->status){
                    DB::table('topup_saldo')->where('topup_id', $id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);

                    Auth::User()->createLog(
                        trans('saldo::topups.logs.delete briva.tipe'), 
                        trans('saldo::topups.logs.delete briva.description', ["brivano" => $data->brivaNo.$data->custCode])
                    );

                    return response()->json([
                        'errors' => false,
                        'message' => trans('saldo::topups.messages.success delete'),
                    ]);
                }else{
                    return response()->json([
                        'errors' => true,
                        'message' => $resultPost->errDesc,
                    ]);   
                }
            }else{
                return response()->json([
                    'errors' => true,
                    'message' => trans('saldo::saldos.messages.error'),
                ]);
            }
        }else{
            return response()->json([
                'errors' => true,
                'message' => trans('saldo::topups.messages.error delete'),
            ]);
        }
    }

    public function settlementBriva(Request $req)
    {
        $status = $req->status;
        $custCode = $req->custCode;
        $paymentDate = $req->paymentDate;
        $data =  DB::table('topup_saldo')->where('custCode', $custCode)->first();
        if($status == 'settlement' && $data != null){
            DB::table('topup_saldo')->where('topup_id', $data->topup_id)
                ->update([
                            'status'=>'success',
                            'tgl_transfer' => $paymentDate,
                            'success_on' => $paymentDate
            ]);
            Saldo::tambah_saldo($data->company_id, $data->amount, "Topup Saldo sebesar Rp. ".number_format($data->amount,0,',','.')." dengan menggunakan BRIVA");
            return response('success',200);
        }
    }

    public function expiredBriva(Request $req)
    {
        $status = $req->status;
        $custCode = $req->custCode;
        $data =  DB::table('topup_saldo')->where('custCode', $custCode)->first();
        if($status == 'expired' && $data != null){
            DB::table('topup_saldo')->where('topup_id', $data->topup_id)
                ->update([
                            'status'=>'expired',
            ]);
            return response('success',200);
        }
    }
}
