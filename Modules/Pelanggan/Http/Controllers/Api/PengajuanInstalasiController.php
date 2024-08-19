<?php

namespace Modules\Pelanggan\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Presale\Entities\Presale;
use Modules\User\Entities\Sentinel\User;
use Modules\Pelanggan\Transformers\PengajuanJadwalInstalasi;
use Modules\Pelanggan\Transformers\SlotInstalasiPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Pelanggan\Events\JadwalInstalasiIsCreated;
use Modules\Pelanggan\Events\JadwalInstalasiPilih;
use Modules\Analytic\Events\Pelanggan\AddJadwalInstalasi;
use Modules\Analytic\Events\Pelanggan\PenentuanJadwalInstalasi;
use Modules\Company\Entities\SlotInstalasi;

class PengajuanInstalasiController extends AdminBaseController
{
	private $slots;
	public function company_name()
	{
		$data = Auth::user()->company();
		return $data['name'];
	}

	public function loadjadwalPengajuanInstalasi($pelanggan_id)
	{
		$pelanggan = new Pelanggan;
		$jadwalPengajuan = $pelanggan->loadPengajuan($pelanggan_id, 'instalasi');
		$pelanggan = Pelanggan::find($pelanggan_id);
		$osp = DB::table('active_presales')->where('presale_id', $pelanggan->presale_id)->first();
		$slot_instalasi = DB::table('slot_instalasi')->where('company_id', $osp->osp_id)
			->whereNull('deleted_at')->get();

		$response = [
			'status' => $pelanggan->status,
			'osp' => $osp,
			'data' => PengajuanJadwalInstalasi::collection($jadwalPengajuan),
			'slot_instalasi' => SlotInstalasiPengajuan::collection($slot_instalasi)
		];
		return Response()->json($response);
	}

	public function getSlotById($slot_id, $osp_id)
	{
		if (!($this->slots)) {
			$this->slots = SlotInstalasi::where('company_id', $osp_id)->get()->toArray();
		}

		foreach ($this->slots as $key => $slot) {
			if ($slot['slot_id'] == $slot_id) {
				return $slot;
			}
		}


		return null;
	}

	public function submitPengajuanInstalasi(Request $req, $pelanggan_id)
	{
		// ubah semua status jadi rechedule
		DB::table("pengajuan_jadwal")->where("pelanggan_id", $pelanggan_id)->update(["status" => "reschedule"]);
		$pelanggan = Pelanggan::where('pelanggan_id', $pelanggan_id)->first();


		if ($req->pengajuan_id != null && $req->pengajuan_id != "") {
			$slot_id = null;
			$tgl_instalasi = null;
			DB::table('pengajuan_jadwal')->where('pengajuan_id', $req->pengajuan_id)->update(["keterangan" => $req->keterangan]);

			$insert = [];
			foreach ($req->list as $key => $val) {
				$insert[] = [
					"pengajuan_id" => $req->pengajuan_id,
					"tgl_instalasi" => $val["tgl_instalasi"],
					"slot_id" => $val["slot_id"],
					"status" => $val["status"],
				];

				if ($val["status"] == "active") {
					$slot_id = $val["slot_id"];
					$tgl_instalasi = $val["tgl_instalasi"];
				}
			}
			if ($slot_id != null) {
				// event penentuan jadwal survey oleh ISP (ISP menentukan jadwal)
				if (Auth::User()->getRoleName() == 'Admin ISP') {
					event(new PenentuanJadwalInstalasi($pelanggan_id));
				}

				// perbarui tanggal pengajuan instalasi
				DB::table('tanggal_pengajuan_instalasi')->where('pengajuan_id', $req->pengajuan_id)->delete();
				DB::table("tanggal_pengajuan_instalasi")->insert($insert);

				$cek = DB::table("instalasi")->where("pelanggan_id", $pelanggan_id)->first();
				if ($cek != null) {
					$update = ['tgl_instalasi' => $tgl_instalasi, 'slot_id' => $slot_id];
					DB::table('instalasi')->where('instalasi_id', $cek->instalasi_id)->update($update);
					DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)->update(['status' => 'instalasi']);
				} else {
					DB::table('instalasi')->insert(['pelanggan_id' => $pelanggan_id, 'tgl_instalasi' => $tgl_instalasi, 'slot_id' => $slot_id]);
					DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)->update(['status' => 'instalasi']);
				}

				// update status pengajuan menjadi aktif
				DB::table("pengajuan_jadwal")->where("pengajuan_id", $req->pengajuan_id)->update(["status" => "accept"]);

				// update sttaus instalasi menjadi active
				DB::table("instalasi")->where("pelanggan_id", $pelanggan_id)->update([
					'status' => 'active'
				]);

				// send mail
				$CreateMailPengajuanJadwalInstalasi = new JadwalInstalasiPilih($pelanggan, $req->pengajuan_id);
			} else {
				// perbarui tanggal pengajuan instalasi
				DB::table('tanggal_pengajuan_instalasi')->where('pengajuan_id', $req->pengajuan_id)->delete();
				DB::table("tanggal_pengajuan_instalasi")->insert($insert);

				// update status pengajuan menjadi pending
				DB::table("pengajuan_jadwal")->where("pengajuan_id", $req->pengajuan_id)->update(["status" => "pending"]);

				// event pemberian jadwal instalasi
				event(new AddJadwalInstalasi($pelanggan_id));

				// send mail
				$jadwals = array_map(function ($data) use ($pelanggan) {
					$slot = $this->getSlotById($data['slot_id'], $pelanggan->osp_id);
					$jam_start = $slot['jam_start'];
					$jam_end = $slot['jam_end'];

					$data['slot_name'] = $slot ? $slot['name'] . " ( $jam_start - $jam_end )" : '';
					return $data;
				}, $insert);

				$CreateMailPengajuanJadwalInstalasi = new JadwalInstalasiIsCreated($pelanggan, ['jadwals' => $jadwals]);
			}
		} else {
			if (Auth::User()->getRoleName() == 'Admin ISP') {
				// event penentuan jadwal instalasi oleh ISP (ISP minta reschedule)
				event(new PenentuanJadwalInstalasi($pelanggan_id));
			}

			// ubah alasan reschedule pada jadwal yang diajukan sebelumnya.
			$pengajuan_jadwal = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan_id)->where('type', 'instalasi');
			$data_sebelumnya = $pengajuan_jadwal->orderBy('pengajuan_id', 'desc')->first();
			DB::table('pengajuan_jadwal')->where('pengajuan_id', $data_sebelumnya->pengajuan_id)->update(['alasan_reschedule' => $req->get('keterangan')]);

			// tambah ke pengajuan jika ada
			$insert = [
				'pelanggan_id' => $pelanggan_id,
				'type' => 'instalasi',
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => Auth::User()->id,
				'status' => 'pending',
			];

			if ($req->get('rekomendasiExist') == 'true') {
				if ($req->get('tgl_rekomendasi') != null && $req->get('tgl_rekomendasi') != '') {
					$insert['tgl_rekomendasi'] = $req->get('tgl_rekomendasi') . " " . $req->get('jam_rekomendasi') . ":00";
				}
			}
			$pengajuan_id = DB::table('pengajuan_jadwal')->insertGetId($insert);

			$insert = [];
			foreach ($req->list as $key => $val) {
				$insert[] = [
					"pengajuan_id" => $pengajuan_id,
					"tgl_instalasi" => $val["tgl_instalasi"],
					"slot_id" => $val["slot_id"],
					"status" => $val["status"],
				];
			}
			DB::table("tanggal_pengajuan_instalasi")->insert($insert);

			$jadwals = array_map(function ($data) use ($pelanggan) {
				$slot = $this->getSlotById($data['slot_id'], $pelanggan->osp_id);
				$jam_start = $slot['jam_start'];
				$jam_end = $slot['jam_end'];

				$data['slot_name'] = $slot ? $slot['name'] . " ( $jam_start - $jam_end )" : '';
				return $data;
			}, $insert);

			// send mail
			$CreateMailPengajuanJadwalInstalasi = new JadwalInstalasiIsCreated($pelanggan, ['jadwals' => $jadwals]);

			//tambah data teknisi instalasi
			$data = [
				$req->teknisi_instalasi
			];
			$instalasi = DB::table("instalasi")->where("pelanggan_id", $pelanggan_id);
			Pelanggan::saveTeknisiInstalasi($data, $instalasi->first()->instalasi_id);
			$instalasi->update([
				'status' => 'reschedule'
			]);
		}

		// send response
		$response = [
			'errors' => false,
			'message' => trans('pelanggan::pengajuanjadwal.messages success'),
		];

		//create log
		Auth::user()->createLog(
			trans('pelanggan::logpelanggan.pengajuan_jadwal.pengajuan jadwal'),
			trans('pelanggan::logpelanggan.pengajuan_jadwal.submit pengajuan instalasi')
		);
		return $response;
	}

	public function pilihRekomendasiInstalasi(Request $req, $pelanggan_id, $pengajuan_id)
	{
		$data = DB::table('pengajuan_jadwal')->where('pengajuan_id', $pengajuan_id)->first();
		$tgl_instalasi = date('Y-m-d', strtotime($data->tgl_rekomendasi));
		$jam_instalasi = date('H:i:s', strtotime($data->tgl_rekomendasi));
		$osp = DB::table('active_presales')->select('active_presales.osp_id')
			->join('pelanggan', 'pelanggan.presale_id', '=', 'active_presales.presale_id')
			->where('pelanggan.pelanggan_id', $pelanggan_id)->first();
		$osp_id = $osp->osp_id;
		$slot_instalasi = DB::table('slot_instalasi')->where('company_id', $osp_id)
			->whereNull('deleted_at')->get();
		$slot_id = null;
		foreach ($slot_instalasi as $key => $val) {
			$jam_awal 	= strtotime($val->jam_start);
			$jam_akhir 	= strtotime($val->jam_end);
			$current 		= strtotime($jam_instalasi);
			if ($jam_awal <= $current && $current <= $jam_akhir) {
				$slot_id = $val->slot_id;
				break;
			}
		}
		if ($slot_id == null) {
			return response()->json([
				'errors' => true,
				'message' => trans('pelanggan::pengajuanjadwal.instalasi.messages.tidak sesuai'),
			]);
		}

		$insert = [
			'pengajuan_id' => $pengajuan_id,
			'tgl_instalasi' => $tgl_instalasi,
			'slot_id' => $slot_id,
			'status' => 'active'
		];
		DB::table('tanggal_pengajuan_instalasi')->insert($insert);

		$cek = DB::table("instalasi")->where("pelanggan_id", $pelanggan_id)->first();

		if ($cek != null) {
			// update instalasi
			$update = ['tgl_instalasi' => $tgl_instalasi, 'slot_id' => $slot_id,];
			DB::table('instalasi')->where('instalasi_id', $cek->instalasi_id)->update($update);

			// Update status pelanggan menjadi instalasi
			DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)->update(['status' => 'instalasi']);

			//create log instalasi
			Auth::user()->createLog(
				trans('pelanggan::logpelanggan.crud.update.title.instalasi'),
				trans('pelanggan::logpelanggan.crud.update.desc.instalasi', ['company' => $this->company_name()])
			);
		} else {
			// tambah instalasi baru	    				
			DB::table('instalasi')
				->updateOrInsert(
					['pelanggan_id' => $pelanggan_id,],
					[
						'pelanggan_id' => $pelanggan_id,
						'tgl_instalasi' => $tgl_instalasi,
						'slot_id' => $slot_id
					]
				);
			DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)->update(['status' => 'instalasi']);

			//create log instalasi
			Auth::user()->createLog(
				trans('pelanggan::logpelanggan.crud.create.title.instalasi'),
				trans('pelanggan::logpelanggan.crud.create.desc.instalasi', ['company' => $this->company_name()])
			);
		}

		DB::table("instalasi")->where("pelanggan_id", $pelanggan_id)->update([
			'status' => 'active'
		]);

		//create teknisi instalasi bertugas 
		$data = [
			$req->all()
		];
		Pelanggan::saveTeknisiInstalasi($data, $cek->instalasi_id);

		// $CreateMailPengajuanJadwalInstalasi = new JadwalInstalasiPilih($pelanggan_id, $pengajuan_id);

		// update status pengajuan menjadi aktif
		DB::table("pengajuan_jadwal")->where("pengajuan_id", $pengajuan_id)->update(["status" => "accept"]);

		//create log
		Auth::user()->createLog(
			trans('pelanggan::logpelanggan.pengajuan_jadwal.pengajuan jadwal'),
			trans('pelanggan::logpelanggan.pengajuan_jadwal.rekomendasi instalasi')
		);

		return response()->json([
			'errors' => false,
			'message' => trans('pelanggan::pengajuanjadwal.messages success'),
		]);
	}
}




// //buat pengajuan baru status accept
// $pengajuan_id_instalasi = DB::table('pengajuan_jadwal')->insertGetId([
// 	'pelanggan_id'=>$pelanggan_id,
// 	'tgl_rekomendasi'=>$req->tgl_rekomendasi,
// 	'type'=>$req->type,
// 	'created_at'=>date('Y-m-d H:i:s'),
// 	'created_by'=>Auth::User()->id,
// 	'status'=>'accept'
// ]);

// //buat pengajuan jadwal instalasi baru
// DB::table('tanggal_pengajuan_instalasi')->insert([
// 	'pengajuan_id'=>$pengajuan_id_instalasi,
// 	'tgl_instalasi'=>$tgl_instalasi,
// 	'status'=>'active',
// 	'slot_id'=>$slot_id,
// 	'created_at'=>date('Y-m-d H:i:s')
// ]);