<?php

namespace Modules\Pelanggan\Http\Controllers\Api;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use DB;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Pelanggan\Entities\PerubahanHarga;
use Modules\Saldo\Entities\Saldo;
use Modules\User\Entities\Sentinel\User;
use Modules\Pelanggan\Transformers\HistoriPerubahanHargaTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Emails\PerubahanHargaDiterima;
use Modules\Pelanggan\Emails\PerubahanHargaDitolak;
use Modules\Pelanggan\Emails\RequestPengubahanJadwal;
use Modules\Analytic\Events\Pelanggan\AccPerubahanHarga;

class PerubahanHargaController{
	public function ShowDataPerubahanHargaSelected(Request $req){
		$user = Auth::User();
		$DataPerubahanHarga = DB::table('perubahan_harga')		
								->select('p.status as status_step','p.paket_id','p.cancel','perubahan_harga.*','pb.paket_id','pb.nama_paket','pb2.nama_paket as nama_paket_perubahan')
								->where('perubahan_harga.pelanggan_id',$req->pelanggan)
								->join('pelanggan as p','p.pelanggan_id','=','perubahan_harga.pelanggan_id')
								->join('paket_berlangganan as pb','pb.paket_id','=','p.paket_id')
								->leftJoin('paket_berlangganan as pb2','pb2.paket_id','perubahan_harga.paket_id')
								->get();
		return HistoriPerubahanHargaTransformer::collection($DataPerubahanHarga);
	}
	public function ajukanPerubahanHarga(Request $req){
		$user = Auth::User();
		$pelanggan = Pelanggan::find($req->id);
		$perubahan_harga = PerubahanHarga::create([
			'pelanggan_id'=>$req->id,
			'harga_otc'=>$req->harga_otc,
			'harga_mrc'=>$req->harga_mrc,
			'created_by'=>$user->id,
			'paket_id' => $req->paket_id,
			'keterangan'=>$req->keterangan,
			'status' => 'request',
			'created_at'=>date('Y-m-d H:i:s')
		]);

		Auth::user()->createLog(
			trans('pelanggan::logpelanggan.perubahan_harga.perubahan harga'),
			trans('pelanggan::logpelanggan.perubahan_harga.ajukan')
		);


		$pelanggan->save();
		$emails = array_merge(
			(new Company())->admin_email($pelanggan->isp_id),
			(new Company())->admin_email($pelanggan->osp_id)
		);
		Mail::to($emails)->send(new RequestPengubahanJadwal($pelanggan, $perubahan_harga));
		return response()->json([
			'errors' => false,
			'message' => trans('pelanggan::surveys.messages.pengajuan pengubahan jadwal sukses')
		]);
	}
	public function terimaPerubahanHarga(Request $req){
		$saldo = new Saldo; 
		$getData = PerubahanHarga::find($req->perubahan_harga_id);
		$getIdPelanggan = $getData->pelanggan_id;
		$dataPelanggan = Pelanggan::find($getIdPelanggan);

		$saldo_biaya_pelanggan = DB::table('saldo_biaya_pelanggan')->where('pelanggan_id',$getIdPelanggan)
																	->where('settlement',0);
		$select_saldo_pelanggan = $saldo_biaya_pelanggan->first();

		if($select_saldo_pelanggan){
			$total_pembayaran_perubahan_harga = $getData->harga_mrc + $getData->harga_otc;
			if($total_pembayaran_perubahan_harga > $select_saldo_pelanggan->saldo_mengendap){
				$isp_id = $dataPelanggan->isp_id;
				$osp_id = $dataPelanggan->osp_id;
				$saldo_pelanggan = DB::table('saldo')->where('company_id',$isp_id)->first();
				
				$jumlah_saldo_pelanggan = $saldo_pelanggan->saldo + $select_saldo_pelanggan->saldo_mengendap;
				$saldo_biaya_pelanggan->update([
					'total_biaya'=>$total_pembayaran_perubahan_harga,
					'saldo_mengendap'=>$total_pembayaran_perubahan_harga,
					'mrc' => $getData->harga_mrc,
					'otc' => $getData->harga_otc
				]);
					
				//perubahan saldo pelanggan
				$kekurangan_saldo = $total_pembayaran_perubahan_harga - $select_saldo_pelanggan->saldo_mengendap;
				$saldo->tambah_saldo_dibekukan(1,$kekurangan_saldo,trans('pelanggan::pelangganinstalasi.controller.menutupi saldo'));
				// $saldo->tambah_saldo(1,$kekurangan_saldo,'menutupi kekurangan saldo setelah terjadi perubahan harga');
				$saldo->potong_saldo($isp_id,$kekurangan_saldo,trans('pelanggan::pelangganinstalasi.controller.pemotongan'));
			}else{
				$saldo_biaya_pelanggan->update([
					'total_biaya'=>$total_pembayaran_perubahan_harga,
				]);
			}

			//PERUBAHAN BIAYA OTC DAN MRC PADA TABLE PELANGGAN
			$dataPelanggan->biaya_mrc = $getData->harga_mrc;
			$dataPelanggan->biaya_otc = $getData->harga_otc;
			if($getData->paket_id){
				$dataPelanggan->paket_id  = $getData->paket_id;
			}
			$dataPelanggan->save();

			//PERUBAHAN STATUS PERUBAHAN HARGA KE ACCETED
			$getData->status = 'accepted';
			$getData->save();
			
			$emails = array_merge(
				(new Company())->admin_email($dataPelanggan->isp_id),
				(new Company())->admin_email($dataPelanggan->osp_id)

			);
			
			// run event
			event(new AccPerubahanHarga($getIdPelanggan));
			// send mail
			Mail::to($emails)->send(new PerubahanHargaDiterima($dataPelanggan));

			//create log
			Auth::user()->createLog(
				trans('pelanggan::logpelanggan.perubahan_harga.perubahan harga'),
				trans('pelanggan::logpelanggan.perubahan_harga.terima')
			);

			return response()->json(['message'=>trans('pelanggan::pelangganinstalasi.controller.accept')]);
		}
	}
	public function tolakPerubahanHarga(Request $req){
		// $user = Auth::User()->id;
		// DB::table('perubahan_harga','created_by');

		$setStatus = PerubahanHarga::find($req->perubahan_harga_id);
		$pelanggan = Pelanggan::find($setStatus->pelanggan_id);
		$setStatus->status = 'rejected';
		$setStatus->save();

		$emails = array_merge(
			(new Company())->admin_email($pelanggan->isp_id),
			(new Company())->admin_email($pelanggan->osp_id)

		);

		// run event
		event(new AccPerubahanHarga($setStatus->pelanggan_id));
		// send mail
		Mail::to($emails)->send(new PerubahanHargaDitolak($pelanggan));

		//create log
		Auth::user()->createLog(
			trans('pelanggan::logpelanggan.perubahan_harga.perubahan harga'),
			trans('pelanggan::logpelanggan.perubahan_harga.tolak')
		);

		return response()->json(['message'=>trans('pelanggan::pelangganinstalasi.controller.reject')]);
	}
}
