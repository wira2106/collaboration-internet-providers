<?php

namespace Modules\Pelanggan\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Pelanggan\Entities\Activation;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Pelanggan\Transformers\DetailAktivasiTransfomer;
use Illuminate\Support\Facades\DB;
use Modules\Analytic\Events\Pelanggan\AktivasiPelanggan;
use Modules\Analytic\Events\Pelanggan\ApproveAktivasiPelanggan;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Transformers\NocForSelectTransformers;

class AktivasiController extends AdminBaseController
{
	public function detailAktivasi($pelanggan_id)
	{
		$aktivasi = new Activation();
		$data = $aktivasi->detail($pelanggan_id);
		$noc_osp = NocForSelectTransformers::collection((new Company())->noc($data->osp_id));
		$pelanggan = Pelanggan::find($pelanggan_id);

		return response()->json([
			'data' => new DetailAktivasiTransfomer($data),
			'noc' => $noc_osp,
			'pelanggan' => $pelanggan
		]);
	}

	public function submitActivation(Request $req)
	{
		$data = Auth::user()->company();
		$company_name = $data['name'];
		// bagi bagi saldo dulu
		$pelanggan = Pelanggan::find($req->pelanggan_id);
		if(!($pelanggan)) {
			return abort(404, trans('pelanggan::pelanggans.messages.404'));
		}

		if($req->status == 'approve'){

			// Proses Aktivasi pelanggan 
			// Terdapat pembagian komisi
			$pelanggan->complete_activation();
		}

		
		// update data aktivasi
		if ($req->aktivasi_id == null) {
			Auth::user()->createLog(
				trans('pelanggan::logpelanggan.crud.create.title.aktivasi'),
				trans('pelanggan::logpelanggan.crud.create.desc.aktivasi',['company'=>$company_name])
			);
			$response = Activation::createActivation($req);
			// send event
			if($req->status_checkbox){
				event(new AktivasiPelanggan($req->pelanggan_id));
			}
		} else {
			Auth::user()->createLog(
				trans('pelanggan::logpelanggan.crud.update.title.aktivasi'),
				trans('pelanggan::logpelanggan.crud.update.desc.aktivasi',['company'=>$company_name])
			);
			$response = Activation::updateActivation($req);
			
			// send event OSP
			if($req->status_checkbox){
				if(Auth::User()->getRoleName() == 'Admin OSP'){
					event(new AktivasiPelanggan($req->pelanggan_id));
				}
			}

			// send event ISP
			if($req->status == 'approve' || $req->status == 'unapprove'){
				if(Auth::User()->getRoleName() == 'Admin ISP'){
					event(new ApproveAktivasiPelanggan($req->pelanggan_id));
				}
			}
		}


	
		return response()->json($response);
	}

	public function unApproveActivation(Request $req)
	{
		$pelanggan_id 	= $req->pelanggan_id;
		$alasan			= $req->alasan;

		// update(['status'=>'proses','keterangan_unapprove' => $alasan]);
		Activation::where('pelanggan_id',$pelanggan_id)->update(['status'=>'unapprove','keterangan_unapprove' => $alasan]);
		Activation::sendMailWhenFinishActivation($pelanggan_id,2);

		// send event
		event(new ApproveAktivasiPelanggan($pelanggan_id));
		
		$pelanggan = Pelanggan::find($pelanggan_id);
		// create log
        Auth::User()->createLog(
            trans('activations.log.update aktivasi.title'), 
            trans('activations.log.update aktivasi.unapprove',['nama_pelanggan' => $pelanggan->nama_pelanggan])
        );

		$response = [
            'errors' => 0,
            'message' => trans('pelanggan::activations.messages.update activation')
        ];

		return response()->json($response);
	}
}