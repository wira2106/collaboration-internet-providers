<?php

namespace Modules\Pelanggan\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Pelanggan\Entities\Activation;
use Modules\Pelanggan\Transformers\PelangganTransformers;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Entities\Sentinel\User;
use Modules\Pelanggan\Entities\Installation;
use Modules\Pelanggan\Transformers\PelangganInstalasi;
use Modules\Pelanggan\Transformers\PerangkatInstalasiPengajuan;
use Modules\Pelanggan\Transformers\JadwalInstalasi;
use Modules\Pelanggan\Transformers\DokumentasiTransformerInstalasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Peralatan\Entities\Alat;
use Modules\Peralatan\Entities\Perangkat;
use Modules\Analytic\Events\Pelanggan\InstalasiPelanggan;

class InstalasiController extends AdminBaseController
{
	public function company_name()
	{
		$data = Auth::user()->company();
		return $data['name'];
	}

	public function ShowDataPelangganSelected(Request $req)
	{
		$DataPelanggan = DB::table('pelanggan')->select('pelanggan.*', 'paket_berlangganan.nama_paket')->where('pelanggan_id', $req->pelanggan)->join('paket_berlangganan', 'paket_berlangganan.paket_id', '=', 'pelanggan.paket_id')->first();
		return new PelangganInstalasi($DataPelanggan);
	}

	//LIST PELANGGAN AKTIF n CANCEL
	public function listPelanggan(Request $req)
	{
		// dd($req->status_pelanggan);
		$ListPelangganAll = DB::table('pelanggan')->join('presales', 'presales.presale_id', '=', 'pelanggan.presale_id')
			->join('paket_berlangganan', 'paket_berlangganan.paket_id', '=', 'pelanggan.paket_id')
			->where('status', $req->status_pelanggan);

		if ($req->get('search') != null) {
			$search = $req->search;
			$ListPelangganAll->where(function ($query) use ($search) {
				$query->where('nama_pelanggan', 'LIKE', "%{$search}%")
					->orWhere('nama_paket', 'LIKE', "%{$search}%")
					->orWhere('telepon', 'LIKE', "%{$search}%")
					->orWhere('email', 'LIKE', "%{$search}%");
			});
		}

		$ListSend = $ListPelangganAll->paginate($req->get('per_page'));

		return PelangganTransformers::collection($ListSend);
	}

	public function showDataInstalasiSelected(Request $req)
	{
		$pelanggan = DB::table('instalasi')->where('pelanggan_id', $req->pelanggan)->first();
		$instalasi_id = $pelanggan->instalasi_id;
		$dataFormInstalasi = new Installation;

		$dataFormInstalasi->showTeknisi($instalasi_id, $req->pelanggan);
		$cancelPelanggan = DB::table('pelanggan')->where('pelanggan_id', $req->pelanggan)->first();

		$data = [
			'status' => $cancelPelanggan->status,
			'cancel' => $cancelPelanggan->cancel,
			'instalasi_id' => $instalasi_id,
			'status_instalasi' => $pelanggan->status,
			'keterangan_instalasi' => $pelanggan->keterangan,
			'data_perangkat' => $dataFormInstalasi->showDataPerangkat($instalasi_id),
			'data_alat' => $dataFormInstalasi->showDataAlat($instalasi_id),
			'dokumentasi' => DokumentasiTransformerInstalasi::collection($dataFormInstalasi->showDokumentasi($instalasi_id)),
			'data_teknisi' => $dataFormInstalasi->showTeknisi($instalasi_id, $req->pelanggan),
			'jadwal_instalasi' => JadwalInstalasi::collection($dataFormInstalasi->jadwalInstalasi($req->pelanggan)),
		];

		return Response()->json($data);
	}

	public function saveTeknisiInstalasi(Request $req)
	{
		$instalasi = DB::table('instalasi')->where('pelanggan_id', $req->pelanggan_id)->first();
		$instalasi_staff = DB::table('instalation_staff');
		$instalasi_staff->where('instalasi_id', $instalasi->instalasi_id)->delete();
		$jumlah_teknisi = count($req->teknisi);
		for ($teknisi = 0; $teknisi < $jumlah_teknisi; $teknisi++) {
			$instalasi_staff = DB::table('instalation_staff');
			$instalasi_staff->insert([
				'instalasi_id' => $instalasi->instalasi_id,
				'user_id' => $req->teknisi[$teknisi]
			]);
		}
		return [
			'error' => false,
			'message' => 'Teknisi bertugas berhasil diperbahrui'
		];
	}

	public function SaveData(Request $req)
	{
		$pelanggan_id = DB::table('instalasi')->where('instalasi_id', $req->instalasi_id)->first()->pelanggan_id;

		$slot_id = DB::table('pengajuan_jadwal')
			->join('tanggal_pengajuan_instalasi', 'tanggal_pengajuan_instalasi.pengajuan_id', '=', 'pengajuan_jadwal.pengajuan_id')
			->where('pelanggan_id', $pelanggan_id)->orderBy('id', 'desc')->first()->slot_id;

		$slot_instalasi = DB::table('slot_instalasi')
			->where('slot_id', $slot_id)->first();

		if($req->status_instalasi == true){
			$set_status_instalasi = 'finish';
		}else{
			$set_status_instalasi = 'active';
		}
		
		// send even
		if($set_status_instalasi == 'finish'){
			$cekUpdate = DB::table('instalasi')->where('instalasi_id',$req->instalasi_id)->first();
			if($cekUpdate->status == 'active'){
				event(new InstalasiPelanggan($pelanggan_id));
			}
		}
		
		DB::table('instalasi')->where('instalasi_id', $req->instalasi_id)->update([
			'jam_start' => $slot_instalasi->jam_start,
			'jam_stop' => $slot_instalasi->jam_end,
			'status' => $set_status_instalasi,
			'keterangan' => $req->keterangan_instalasi
		]);

		if ($req->dokumentasi_instalasi !== null && $req->dokumentasi_instalasi !== '') {
			$file = $req->file('dokumentasi_instalasi');
			$a = $req->dokumentasi_instalasi;
			$jumlah_dokumentasi_form = count($req->dokumentasi_instalasi);
			for ($img = 0; $img < $jumlah_dokumentasi_form; $img++) {
				$dokumentasi_in_database = DB::table('dokumentasi_instalasi')->where('id', $req->dokumentasi_instalasi[$img]['dokumentasi_id']);
				if ($req->dokumentasi_instalasi[$img]['fotoUpload'] != null) {
					$namaFILE = ImageController::uploadFoto($req->file('dokumentasi_instalasi')[$img]['fotoUpload']['raw']);
					if ($a[$img]['dokumentasi_id'] != null && $a[$img]['fotoUpload'] != null) {
						$dokumentasi_in_database->update([
							'foto_dokumentasi' => $namaFILE,
							'keterangan' => $req->dokumentasi_instalasi[$img]['keterangan']
						]);
					} else {
						DB::table('dokumentasi_instalasi')->insert([
							'instalasi_id' => $req->instalasi_id,
							'foto_dokumentasi' => $namaFILE,
							'keterangan' => $req->dokumentasi_instalasi[$img]['keterangan']
						]);
					}
				} else {
					if ($a[$img]['dokumentasi_id'] != '' && $a[$img]['keterangan'] != '') {
						$dokumentasi_in_database->update([
							'keterangan' => $req->dokumentasi_instalasi[$img]['keterangan']
						]);
					}
				}
			}
		}


		$req_alat = $req->alat_instalasi_digunakan;
		if ($req_alat) {

			$alat = DB::table('alat_instalasi')->where('instalasi_id', $req->instalasi_id);
			$alat->delete();
			$jumlahAlat = count($req_alat);
			for ($n = 0; $n < $jumlahAlat; $n++) {
				$alat->insert([
					'instalasi_id' => $req->instalasi_id,
					'alat_id' => $req_alat[$n]['alat_id'],
					'qty' => $req_alat[$n]['qty'],
					'status' => $req_alat[$n]['status']
				]);
			}
		}

		$req_perangkat = $req->perangkat_instalasi_digunakan;
		if ($req_perangkat) {
			$perangkat = DB::table('perangkat_instalasi')->where('instalasi_id', $req->instalasi_id);
			$perangkat->delete();
			$jumlahPerangkat = count($req_perangkat);
			for ($i = 0; $i < $jumlahPerangkat; $i++) {
				$perangkat->insert([
					'instalasi_id' => $req->instalasi_id,
					'perangkat_id' => $req_perangkat[$i]['perangkat_id'],
					'jenis_perangkat' => $req_perangkat[$i]['jenis_perangkat'],
					'qty' => $req_perangkat[$i]['qty'],
					'status' => $req_perangkat[$i]['status']
				]);
			}
		}


		//create log
		Auth::user()->createLog(
			trans('pelanggan::logpelanggan.crud.update.title.instalasi'),
			trans('pelanggan::logpelanggan.crud.update.desc.instalasi', ['company' => $this->company_name()]),
			null
		);
		$respond_next_false = [
			'next' => false,
			'error' => false,
			'message' => 'saved successfully'
		];

		if ($req->status_instalasi == true) {
			$status_pelanggan = DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id);
			$status_pelanggan->update([
				'status' => 'aktivasi'
			]);
			return response()->json([
				'next' => true,
				'error' => false,
				'message' => 'saved successfully'
			]);
		} else {
			return response()->json($respond_next_false);
		}
	}
	public function DeleteAlatOrPerangkatOrDokumentasi(Request $req)
	{
		if ($req->alat_id != '') {
			$selectAlat = DB::table('alat_instalasi')->where('id', $req->alat_id);
			if ($selectAlat->count()) {
				$selectAlat->delete();
				Auth::user()->createLog(
					trans('pelanggan::logpelanggan.crud.delete.title.alat'),
					trans('pelanggan::logpelanggan.crud.delete.desc.alat')
				);
				return response()->json(['message' => 'Success', 'error' => false,]);
			}
		} else if ($req->perangkat_id != '') {
			$selectPerangkat = DB::table('perangkat_instalasi')->where('id', $req->perangkat_id);
			if ($selectPerangkat->count()) {
				$selectPerangkat->delete();
				Auth::user()->createLog(
					trans('pelanggan::logpelanggan.crud.delete.title.perangkat'),
					trans('pelanggan::logpelanggan.crud.delete.desc.perangkat')
				);
				return response()->json(['message' => 'success', 'error' => false]);
			}
		} else if ($req->dokumentasi_id) {
			$selectDokumentasi = DB::table('dokumentasi_instalasi')->where('id', $req->dokumentasi_id);
			if ($selectDokumentasi->count()) {
				$selectDokumentasi->delete();
				Auth::user()->createLog(
					trans('pelanggan::logpelanggan.crud.delete.title.dokumentasi'),
					trans('pelanggan::logpelanggan.crud.delete.desc.dokumentasi')
				);
				return response()->json(['message' => 'success', 'error' => false]);
			}
		} else {
			return response()->json([
				'message' => 'Data selected not found',
				'error' => true
			]);
		}
	}

	public function showDataPerangkatAlatTeknisiDokumentasi(Request $req)
	{
		//select data perangkat
		$dataPerangkat = Perangkat::orderBy('nama_perangkat')->get();

		//select data alat
		$dataAlat = Alat::all();

		//select data instalasi staff menurut osp id
		$DataPelanggan = DB::table('pelanggan')->where('pelanggan_id', $req->pelanggan)->first();
		$osp_id = $DataPelanggan->osp_id;

		//data teknisi
		$dataTeknisi = Pelanggan::getOptionTeknisi($osp_id);


		// dokumentasi
		$pelanggan = DB::table('instalasi')->where('pelanggan_id', $req->pelanggan)->first();
		// dd($dataAlat);
		$instalasi_id = $pelanggan->instalasi_id;
		$dataDokumentasi = new Installation;

		return response()->json(
			[
				'data_perangkat' => $dataPerangkat,
				'data_alat' => $dataAlat,
				'data_teknisi' => $dataTeknisi,
				'dokumentasi' => DokumentasiTransformerInstalasi::collection($dataDokumentasi->showDokumentasi($instalasi_id))
			]
		);
	}
}