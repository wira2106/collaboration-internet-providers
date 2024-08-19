<?php

namespace Modules\Pelanggan\Entities;

use Illuminate\Http\Request;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Pelanggan\Emails\FinishActivationMail;
use Modules\Pelanggan\Emails\ApproveActivationMail;
use Modules\Pelanggan\Emails\UnApproveActivationMail;
use Auth;

class Activation extends Model
{
    use Translatable;

    protected $table = 'aktivasi';
    public $translatedAttributes = [];
    protected $fillable = [];
    protected $primaryKey = "aktivasi_id";
    public $timestamps = false;

    public function detail($pelanggan_id)
    {
        $data = Self::select([
                                'aktivasi.*', 
                                'pelanggan.osp_id', 
                                'pelanggan.pelanggan_id', 
                                'pelanggan.status as status_step'
                             ])
            ->rightJoin('pelanggan', 'pelanggan.pelanggan_id', '=', 'aktivasi.pelanggan_id')
            ->where('pelanggan.pelanggan_id', $pelanggan_id)
            ->first();
        if ($data->aktivasi_id != null) {
            $data_noc = DB::table('noc_aktivasi')
                        ->select(['noc_aktivasi.user_id', 'users.full_name as name'])
                        ->where('noc_aktivasi.aktivasi_id', $data->aktivasi_id)
                        ->join('users', 'users.id', 'noc_aktivasi.user_id')
                        ->get();
            $data->noc = $data_noc;
        } else {
            $data->noc = [];
        }
        

        return $data;
    }

    public function getNOCOSP($osp_id)
    {
        $noc = (new Company())->noc($osp_id);
        
        dd($noc);
        $send = [];
        return $send;
    }

    public static function createActivation(Request $req)
    {
        if($req->status_checkbox == true){
            $status_aktivasi = 'selesai';
        }else{
            $status_aktivasi = 'proses';
        }

        // insert ke aktivasi        
        $insert = [
            'pelanggan_id' => $req->pelanggan_id,
            'tgl_aktivasi' => $req->tgl_aktivasi,
            'keterangan' => $req->keterangan,
            'status' => $status_aktivasi
        ];
        $aktivasi_id = DB::table('aktivasi')->insertGetId($insert);
        // tambah noc bertugas
        $noc = $req->noc;
        $insert = [];
        foreach ($noc as $key => $val) {
            $insert[] = [
                'aktivasi_id' => $aktivasi_id, 
                'user_id' => $val
            ];
        }
        DB::table('noc_aktivasi')->insert($insert);

        // set if selesai
        $generateToDetail = false;
        $cek = Pelanggan::find($req->pelanggan_id);
        if ($cek->status == 'aktivasi') {
            if ($req->status == 'cancel') {
                DB::table('pelanggan')->where('pelanggan_id', $req->pelanggan_id)->update(['status' => 'aktivasi', 'cancel' => 1]);
            } else {
                DB::table('pelanggan')->where('pelanggan_id', $req->pelanggan_id)->update(['status' => 'aktivasi', 'cancel' => 0]);
            }

            // send mail
            if($req->status == 'selesai'){
                Self::sendMailWhenFinishActivation($req->pelanggan_id);
            }
        }

        // create log
        Auth::User()->createLog(
            trans('activations.log.update aktivasi.title'), 
            trans('activations.log.update aktivasi.desc',['nama_pelanggan' => $cek->nama_pelanggan])
        );

        $response = [
            'errors' => 0,
            'message' => trans('pelanggan::activations.messages.create activation'),
            'redirect_detail' => $generateToDetail
        ];
        return $response;
    }
    public static function updateActivation(Request $req)
    {
        if($req->status_checkbox == true){
            $status_aktivasi = 'selesai';
        }else{
            $status_aktivasi = 'proses';
        }

        // update aktivasi        
        $update = [
            'pelanggan_id' => $req->pelanggan_id,
            'tgl_aktivasi' => $req->tgl_aktivasi,
            'keterangan' => $req->keterangan,
            'status' => $status_aktivasi,
            'keterangan_unapprove' => null
        ];
        DB::table('aktivasi')->where('aktivasi_id', $req->aktivasi_id)->update($update);

        // tambah noc bertugas
        $noc = $req->noc;
        $insert = [];
        foreach ($noc as $key => $val) {
            $insert[] = [
                            'aktivasi_id' => $req->aktivasi_id, 
                            'user_id' => $val
                        ];
        }
        DB::table('noc_aktivasi')->where('aktivasi_id', $req->aktivasi_id)->delete();
        DB::table('noc_aktivasi')->insert($insert);

        // set if selesai
        $generateToDetail = false;
        $cek = Pelanggan::find($req->pelanggan_id);
        if ($cek->status == 'aktivasi') {
            if ($req->status == 'approve') {
                DB::table('pelanggan')->where('pelanggan_id', $req->pelanggan_id)
                                        ->update(['status' => 'aktif','cancel'=>0,'suspend_id'=>null]);
                $generateToDetail = true;
            } else if ($req->status == 'cancel') {
                DB::table('pelanggan')->where('pelanggan_id', $req->pelanggan_id)
                                        ->update(['status' => 'aktivasi', 'cancel' => 1,'suspend_id'=>null]);
            } else {
                DB::table('pelanggan')->where('pelanggan_id', $req->pelanggan_id)
                                        ->update(['status' => 'aktivasi', 'cancel' => 0,'suspend_id'=>null]);
            }


            // send mail
            if($req->status == 'selesai'){
                Self::sendMailWhenFinishActivation($req->pelanggan_id);
            }else if($req->status == 'approve'){
                Self::sendMailWhenFinishActivation($req->pelanggan_id,1);
            }
        }

        // create log
        Auth::User()->createLog(
            trans('activations.log.update aktivasi.title'), 
            trans('activations.log.update aktivasi.desc',['nama_pelanggan' => $cek->nama_pelanggan])
        );

        $response = [
            'errors' => 0,
            'message' => trans('pelanggan::activations.messages.update activation'),
            'redirect_detail' => $generateToDetail
        ];
        return $response;
    }


    static function sendMailWhenFinishActivation($pelanggan_id, $approve=0)
    {
        // approve 0 = belum approve
        // approve 1 = di approve
        // approve 2 = tidak di approve

        $company = new Company;
        $pelanggan  = Pelanggan::find($pelanggan_id)->withPacket();
        $company    = Company::find(($approve == 1?$pelanggan->osp_id:$pelanggan->isp_id));
        $aktivasi   = DB::table('aktivasi')->where('pelanggan_id',$pelanggan_id)->first();
        $admin_mail = $company->admin_email(($approve == 1?$pelanggan->osp_id:$pelanggan->isp_id));

        $send = [
            'pelanggan' => $pelanggan,
            'company' => $company,
            'aktivasi' => $aktivasi
        ];

        $url = route('admin.pelanggan.form.step',['pelanggan'=>$pelanggan_id]);
        if($approve == 1){
            $url = route('admin.pelanggan.form.detail',['pelanggan_id'=>$pelanggan_id]);
        }
        $data = [
            "title" => trans('pelanggan::activations.mail.title.aktivasi pelanggan'),
            "data" => $send,
            "url" => route('admin.pelanggan.form.step',['pelanggan'=>$pelanggan_id]),
        ];
       
        if($approve == 1){
            Mail::to($admin_mail)->send(new ApproveActivationMail($data));
        }else if($approve == 2){
            Mail::to($admin_mail)->send(new UnApproveActivationMail($data));
        }else{
            Mail::to($admin_mail)->send(new FinishActivationMail($data));
        }
        
    }
}