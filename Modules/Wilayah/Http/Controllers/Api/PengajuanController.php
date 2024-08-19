<?php

namespace Modules\Wilayah\Http\Controllers\Api;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Wilayah\Entities\Wilayah;
use Modules\Wilayah\Entities\Pengajuan;
use Modules\Presale\Entities\Presale;
use Modules\Wilayah\Http\Requests\CreateWilayahRequest;
use Modules\Wilayah\Http\Requests\UpdateWilayahRequest;
use Modules\Wilayah\Repositories\WilayahRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Utils\Http\Controllers\UtilsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Wilayah\Emails\NotifPengajuanWilayah;
use Modules\Company\Entities\Company;
use Modules\Mail\Http\Controllers\Api\MailController;
use Modules\Wilayah\Emails\PengajuanIsConfirmed;
use Modules\Wilayah\Transformers\ListAlasanTransformer;
use Modules\Wilayah\Transformers\WilayahTransformer;
use Modules\Wilayah\Transformers\ListWilayahTransformer;
use Modules\Wilayah\Transformers\ListPengajuanTransformer;
use Modules\Wilayah\Transformers\WilayahInfoPengajuan;
use Modules\Wilayah\Transformers\WilayahDetailPengajuan;
use Modules\Wilayah\Transformers\ListPaketPengajuanTransformer;


class PengajuanController extends AdminBaseController
{
  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      $this->log =  Auth::user();
      return $next($request);
    });
  }

  public function userEmail($id_company)
  {
    $data = DB::table('user_companies as a')
      ->join('users as b', 'a.user_id', '=', 'b.id')
      ->select('a.*', 'b.email')
      ->where('company_id', '=', $id_company)
      ->get();
    return $data;
  }

  public function uploadFile($data)
  {
    $uploadId = array();
    $destinationPath = 'uploadfile';
    foreach ($data->file_kontrak as $key => $file) {
      $extension = $file->getClientOriginalExtension();
      $name = date('Y-m-d') . '-' . $data->nama_osp . rand(0, 9999) . '.' . $extension;
      $file->move($destinationPath, $name);
      $uploadId[] = $name;
    }
    return $uploadId;
  }


  public function submit(Request $req, $wilayah_id)
  {
    $model = new Wilayah;
    $wilayah = Wilayah::find($wilayah_id);

    if ($req->company_id == null) {
      $company_id = Auth::user()->company()->company_id;
    } else {
      $company_id = $req->company_id;
    }

    // cek ketersediaan
    $cek = $this->checkPengajuan($wilayah_id, $company_id);

    if (!$cek) {
      return response()->json([
        'errors' => true,
        'message' => trans("wilayah::pengajuans.messages.pengajuan exist")
      ]);
    }
    $isp = Company::find($company_id);
    $osp = Auth::user()->company();
    $insert = [
      "isp_id" => $company_id,
      "osp_id" => $wilayah->company_id,
      "wilayah_id" => $wilayah->wilayah_id,
      "status" => "request",
      "alasan" => $req->alasan,
      "created_at" => date("Y-m-d H:i:s")
    ];
    $mail = [
      'company_id' => $wilayah->company_id,
      'isp' => $isp->name,
      'osp' => $osp->name,
      'status' => "request",
      'alasan' => $req->alasan,
      'alasan_batal' => '',
      'tgl_konfirmasi' => date('Y-M-d H:i'),
      'tgl_email' => date('M d, Y'),
      'wilayah' => $wilayah->name,
      'success' => false
    ];
    MailController::sendEmailOSP($mail);

    // foreach ($this->userEmail($wilayah->company_id) as $key => $value) {
    //   Mail::to($value->email)->send(new NotifPengajuanWilayah($mail));
    // }
    
    
    $request_wilayah_id = DB::table("request_wilayah")->insertGetId($insert);
    $kontrak_wilayah = DB::table('kontrak_wilayah')->insert(['request_wilayah_id' => $request_wilayah_id]);

    $this->log->createLog(
      trans('wilayah::pengajuans.logs.create.tipe'), 
      trans('wilayah::pengajuans.logs.create.description'),
      $company_id
    );

    return response()->json([
      'errors' => false,
      'message' => trans("wilayah::pengajuans.messages.pengajuan created")
    ]);
  }

  public function checkPengajuan($wilayah_id, $company_id)
  {
    $cek = DB::table('request_wilayah')->where('isp_id', $company_id)
      ->where('wilayah_id', $wilayah_id);

    if ($cek->count() > 0 &&  !($cek->first()->status == 'rejected')) {
      return false;
    } else {
      return true;
    }
  }

  public function pagination(Request $request)
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

    return ListPengajuanTransformer::collection($data)->additional($allDataCompany);
  }

  public function serverPaginationFilteringFor($company_id, Request $request)
  {
    $select = [
      'a.*',
      'b.name as nama_osp',
      'c.name as nama_isp',
      'd.name as nama_wilayah',
      'd.open as open',
      'e.*'
    ];
    $data    = DB::table('request_wilayah as a')->select($select)
      ->join('wilayah as d', 'd.wilayah_id', '=', 'a.wilayah_id')
      ->join('companies as b', 'b.company_id', '=', 'a.osp_id')
      ->join('companies as c', 'c.company_id', '=', 'a.isp_id')
      ->join('kontrak_wilayah as e', 'e.request_wilayah_id', '=', 'a.request_wilayah_id')
      ->where('e.active', 1);

    if ($company_id != null && $company_id != "") {
      $company = Company::find($company_id);
      if ($company->type == 'osp') {
        $data = $data->where('osp_id', $company->company_id);
      } else {
        $data = $data->where('isp_id', $company->company_id);
      }
    }

    if ($request->status != null && $request->status != "") {
      $data = $data->where('status', $request->status);
    }


    if ($request->get('search') !== null) {
      $term = $request->get('search');
      $data->where(function ($query) use ($term) {
        $query->orWhere('d.name', 'LIKE', "%{$term}%")
          ->orWhere('b.name', 'LIKE', "%{$term}%")
          ->orWhere('c.name', 'LIKE', "%{$term}%");
      });
    }

    if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
      $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

      $data->orderBy($request->get('order_by'), $order);
    } else {
      $data->orderBy('a.created_at', 'desc');
    }

    return $data->paginate($request->get('per_page', 10));
  }

  public function checkPaket($id)
  {
    $jml_paket = DB::table('paket_for_isp')->where('request_wilayah_id', $id)->count();
    return response()->json([
      'jumlah_paket' => $jml_paket
    ]);
  }

  public function setStatus(Request $req)
  {
    // dd($req->all());
    $end_date = date('Y-m-d', strtotime('+' . $req->lama_kontrak . ' months', strtotime($req->start_date)));
    $step = 0;
    $id     = $req->request_wilayah_id;
    $status = $req->status;
    // migrate terlebih dahulu ,ada penambahan column accepted_at
    $update = ['status' => $status, 'accepted_at' => null];
    $request_wilayah = DB::table('request_wilayah')->where('request_wilayah_id', $id);
    $kontrak_wilayah = DB::table('kontrak_wilayah')->where('request_wilayah_id', $id);
    $alasan_lama = $request_wilayah->first()->alasan;
    
    $mail = [
      'company_id' => $req->isp_id,
      'isp' => $req->nama_isp,
      'osp' => $req->nama_osp,
      'status' => "",
      'alasan' => $req->alasan,
      'alasan_batal' => '',
      'tgl_konfirmasi' => date('Y-M-d H:i'),
      'tgl_email' => date('M d, Y'),
      'wilayah' => $req->nama_wilayah,
      'success' => false
    ];

    

    if ($req->status == "request") {
      $step = 1;
    }

    if ($req->status == "confirmed") {
      $step = 2;
      $mail['status'] = $req->status;
      $wilayah = DB::table('request_wilayah')
                  ->where('request_wilayah_id', $id)
                  ->join('wilayah', 'wilayah.wilayah_id', 'request_wilayah.wilayah_id')
                  ->first();

      $osp = Company::find($wilayah->osp_id);

      $admin_emails = (new Company)->admin_email($wilayah->isp_id);
      Mail::to($admin_emails)->send(new PengajuanIsConfirmed($wilayah, $osp));
    }

    if ($req->status == "disagree") {
      $step = 3;
      $mail['status'] = $req->status;
      $mail['company_id'] = $req->osp_id;
      $mail['alasan_batal'] = $req->alasan;
      $mail['alasan'] = $alasan_lama;
      
    }

    if ($req->status == "accepted") {
      $step = 3;
      $update['accepted_at'] = date('Y-m-d H:i:s');
      $mail['company_id'] = $req->osp_id;
      $mail['status'] = $req->status;
      $mail['success'] = true;
    }
    if ($req->status == "rejected") {
      $step = 0;
      $mail['status'] = $req->status;
      $mail['alasan_batal'] = $req->alasan;
      $mail['alasan'] = $alasan_lama;
    }

    if($req->status == 'rejected' || $req->status == 'disagree' || $req->status == 'request') {
      DB::table('alasan_request_wilayah')
      ->insert([
        'request_wilayah_id' => $req->request_wilayah_id,
        'alasan' => $req->alasan,
        'status' => $req->status,
        'created_at' => date('Y-m-d H:i:s')
      ]);
    }


    MailController::sendEmailOSP($mail);

    if ($step > 0 && $step < 3) {
      $file_kontrak = $this->uploadFile($req);
      $kontrak = [
        'file_kontrak' => json_encode($file_kontrak),
        'toleransi_tunggakan' => $req->toleransi,
        'start_date' => $req->start_date,
        'end_date' => $end_date,
        'deskripsi_kontrak' => 'Syarat & ketentuan Berlaku',
        'request_wilayah_id' => $req->request_wilayah_id
      ];
      // $kontrak_wilayah->updateOrInsert(['request_wilayah_id' => $req->request_wilayah_id], $kontrak);
      $udpate_kontrak_wilayah = DB::table('kontrak_wilayah')->where('request_wilayah_id', $req->request_wilayah_id)->update([
        'active' => 0
      ]);
      $create_kontrak_wilayah = DB::table('kontrak_wilayah')->insert($kontrak);
    }

    if ($step >= 0) {
      $request_wilayah = $request_wilayah->update($update);
    }


    $this->log->createLog(
      trans('wilayah::pengajuans.logs.edit.tipe'), 
      trans('wilayah::pengajuans.logs.edit.description', [
        'data' => $status
      ])
    );

    return response()->json([
      'errors' => false,
      'message' => trans("wilayah::pengajuans.messages.pengajuan updated"),
    ]);
  }

  public function detail($id)
  {
    $pengajuan = new Pengajuan;
    $data = $pengajuan->detailPengajuan($id);
    return new ListPengajuanTransformer($data);
  }

  public function paketIsp(Request $req)
  {
    $pengajuan = new Pengajuan;

    $id     = $req->id;
    $data_pengajuan = $pengajuan->detailPengajuan($id);
    $paket  = $req->paket;
    $isp_id = $data_pengajuan->isp_id;
    $request_id = $data_pengajuan->request_wilayah_id;

    $paket_isp = DB::table('paket_for_isp as a')
      ->join('request_wilayah as b', 'b.request_wilayah_id', '=', 'a.request_wilayah_id')
      ->where('b.request_wilayah_id', $request_id)
      ->where('b.isp_id', $isp_id)->get();

    foreach ($paket_isp as $key => $value) {
      if (in_array($value->paket_id, $paket)) {
        unset($paket[array_search($value->paket_id, $paket)]);
      }
    }
    $dataPaketIsp = [];
    foreach ($paket as $key => $value) {
      $dataPaketIsp[] = [
        'request_wilayah_id' => $request_id, 
        'paket_id' => $value
      ];
    }

    DB::table('paket_for_isp')->insert($dataPaketIsp);
    $isp = Company::find($data_pengajuan->isp_id);
    $this->log->createLog(
      trans('wilayah::pengajuans.logs.create pengajuan paket.tipe'), 
      trans('wilayah::pengajuans.logs.create pengajuan paket.description', [
        'wilayah_name' => $data_pengajuan->nama_wilayah,
        'isp_name' => $isp->name
      ]),
      $data_pengajuan->osp_id
    );
    return response()->json([
      'errors' => false,
      'message' => trans("wilayah::pengajuans.messages.paket added")
    ]);
  }

  public function paketPagination(Request $req)
  {
    $data = $this->serverPaginationPaket($req);
    return ListPaketPengajuanTransformer::collection($data);
  }

  public function serverPaginationPaket(Request $request)
  {
    $data = DB::table("paket_for_isp as a")
      ->join("paket_berlangganan as b", "b.paket_id", "=", "a.paket_id")
      ->where('a.request_wilayah_id', $request->id_request);
    if ($request->get('search') !== null) {
      $term = $request->get('search');
      $data->where(function ($query) use ($term) {
        $query->orWhere('b.nama_paket', 'LIKE', "%{$term}%");
      });
    }

    if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
      $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

      $data->orderBy($request->get('order_by'), $order);
    } else {
      $data->orderBy('b.nama_paket', 'asc');
    }

    return $data->paginate($request->get('per_page', 10));
  }

  public function paketIspAll($pengajuan_id)
  {
    $data = DB::table('paket_for_isp')->select(['paket_id'])
      ->where('request_wilayah_id', $pengajuan_id)
      ->get();

    return response()->json($data);
  }

  public function deletePengajuan(Pengajuan $id)
  {
    $wilayah = Wilayah::find($id->wilayah_id);
    $id->delete();


    $this->log->createLog(
      trans('wilayah::pengajuans.logs.destroy.tipe'),
      trans('wilayah::pengajuans.logs.destroy.description', [
        'data' => $wilayah->name
      ]),
      $id->isp_id
    );

    return response()->json([
      'errors' => false,
      'message' => trans("wilayah::pengajuans.messages.pengajuan deleted")
    ]);
  }

  public function deletePaketPengajuan($paket_id, $pengajuan_id)
  {
    $data = DB::table('paket_for_isp')
            ->where('paket_for_isp.request_wilayah_id', $pengajuan_id)
            ->where('paket_for_isp.paket_id', $paket_id) ;

    $data_lengkap = $data;
    
    // dd($data_lengkap->get());
    $data_lengkap = $data_lengkap->select([
                            'companies.name as company_name',
                            'wilayah.name as wilayah_name',
                            'paket_berlangganan.nama_paket',
                            'request_wilayah.osp_id'
                          ])
                          ->join('request_wilayah', 'request_wilayah.request_wilayah_id', 'paket_for_isp.request_wilayah_id')
                          ->join('companies', 'companies.company_id', 'request_wilayah.isp_id')
                          ->join('paket_berlangganan', 'paket_berlangganan.paket_id', 'paket_for_isp.paket_id')
                          ->join('wilayah', 'wilayah.wilayah_id', 'request_wilayah.wilayah_id')
                          ->first();

    $data->delete();

    $this->log->createLog(
      trans('wilayah::pengajuans.logs.destroy pengajuan paket.tipe'), 
      trans('wilayah::pengajuans.logs.destroy pengajuan paket.description', [
        'paket' => $data_lengkap->nama_paket,
        'isp_name' => $data_lengkap->company_name,
        'wilayah_name' => $data_lengkap->wilayah_name
      ]),
      $data_lengkap->osp_id
    );

    return response()->json([
      'errors' => false,
      'message' => trans("wilayah::pengajuans.messages.paket deleted")
    ]);
  }

  public function dataKontrak($id)
  {
    $data = DB::table('kontrak_wilayah')->where('request_wilayah_id', '=', $id)->first();
    return response()->json($data);
  }

  public function perpanjang_kontrak(Pengajuan $request_wilayah) {
    
    $request_wilayah->status = 'request';
    $request_wilayah->save();

    DB::table('alasan_request_wilayah')
    ->insert([
      'request_wilayah_id' => $request_wilayah->request_wilayah_id,
      'status' => 'request',
      'alasan' => 'Perpanjang kontrak',
      'created_at' => date('Y-m-d H:i:s')
    ]);

    return response()->json([
      'errors' => false,
      'message' => trans('wilayah::pengajuans.messages.perpanjang kontrak success')
    ]);
  }

  public function pagination_alasan($request_wilayah_id, Request $request) 
  {
    return ListAlasanTransformer::collection($this->serverPaginationAlasan($request_wilayah_id, $request));
  }

  public function serverPaginationAlasan($request_wilayah_id, $request) : LengthAwarePaginator 
  {
    $data = DB::table('alasan_request_wilayah')
    ->where('request_wilayah_id', $request_wilayah_id);

    return $data->paginate($request->get('per_page', 10));
  }
}
