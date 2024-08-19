<?php

namespace Modules\Pelanggan\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Presale\Entities\Presale;
use Modules\Pelanggan\Transformers\PengajuanJadwalSurvey;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\Sentinel\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Emails\PengajuanJadwalSurveyDisetujui;
use Modules\Pelanggan\Emails\TambahPengajuanJadwalSurvey;
use Modules\Analytic\Events\Pelanggan\AddJadwalSurvey;
use Modules\Analytic\Events\Pelanggan\PenentuanJadwalSurvey;
use Modules\Pelanggan\Entities\Survey;

class PengajuanSurveyController extends AdminBaseController
{
	public function company_name()
	{
		$data = Auth::user()->company();
		return $data['name'];
	}

	public function loadjadwalPengajuanSurvey($pelanggan_id)
	{

		$pelanggan = new Pelanggan;
		$jadwalPengauan = $pelanggan->loadPengajuan($pelanggan_id, 'survey');
		$pelanggan = Pelanggan::find($pelanggan_id);
		$survey = Survey::where('pelanggan_id', '=', $pelanggan_id)->first();
		if ($survey !== null) {
			$teknisi = (new Survey)->getTeknisiSurvey($survey->id);
		} else {
			$teknisi = [];
		}

		$list_teknisi = Pelanggan::getOptionTeknisi($pelanggan->osp_id);
		$response = [
			'status' => $pelanggan->status,
			'osp' => $pelanggan->osp_id,
			'data' => PengajuanJadwalSurvey::collection($jadwalPengauan),
			'teknisi' => $teknisi,
			'list_teknisi' => $list_teknisi,
		];

		return Response()->json($response);
	}


	public function submitPengajuanSurvey(Request $req, $pelanggan_id)
	{
		DB::beginTransaction();
		// dd($req->all());
		// ubah semua status jadi rechedule
		try {

			DB::table("pengajuan_jadwal")->where("pelanggan_id", $pelanggan_id)->update(["status" => "reschedule"]);
			$data_pengajuan_terakhir = DB::table('pengajuan_jadwal')
				->where('pengajuan_jadwal.pelanggan_id', $pelanggan_id)
				->latest('pengajuan_id')
				->first();
			$pelanggan = Pelanggan::find($pelanggan_id);
			if ($req->pengajuan_id != null && $req->pengajuan_id != "") {
				$tanggalSurvey = null;

				$insert = [];
				foreach ($req->list as $key => $val) {
					$insert[] = [
						"pengajuan_id" => $req->pengajuan_id,
						"tgl_survey" => $val["tgl_survey"],
						"jam_survey" => $val["jam_survey"],
						"status" => $val["status"]
					];

					if ($val["status"] == "active") {
						$tanggalSurvey = $val["tgl_survey"] . " " . $val["jam_survey"];
					}
				}

				if ($tanggalSurvey != null) {

					// event penentuan jadwal survey oleh ISP (ISP menentukan jadwal)
					if (Auth::User()->getRoleName() == 'Admin ISP') {
						event(new PenentuanJadwalSurvey($pelanggan_id));
					}


					// perbaruai tanggal pengajuan survey
					DB::table('tanggal_pengajuan_survey')->where('pengajuan_id', $req->pengajuan_id)->delete();
					DB::table("tanggal_pengajuan_survey")->insert($insert);

					$cek = DB::table("survey")->where("pelanggan_id", $pelanggan_id)->first();
					if ($cek != null) {
						// update survey
						DB::table('survey')->where('id', $cek->id)->update(['tgl_survey' => $tanggalSurvey]);
						DB::table('pelanggan')
							->where('pelanggan_id', $pelanggan_id)
							->update([
								'status' => 'survey',
							]);
					}


					//Membuat pengajuan jadwal baru dgn status accept (tidak di pake)
					// DB::table("pengajuan_jadwal")->where("pengajuan_id", $req->pengajuan_id)->update(["status" => "reschedule"]);


					// $id_pengajuan = DB::table("pengajuan_jadwal")
					// 	->where("pengajuan_id", $req->pengajuan_id)
					// 	->insertGetId([
					// 		"pelanggan_id" =>  $pelanggan_id,
					// 		"type" => "survey",
					// 		"created_at" => date('Y-m-d H:i:s'),
					// 		"created_by" => Auth::user()->id,
					// 		"status" => "accept",
					// 	]);

					// foreach ($insert as $key => $value) {
					// 	$value['pengajuan_id'] = $id_pengajuan;
					// 	DB::table("tanggal_pengajuan_survey")->insert($value);
					// }

					// update status pengajuan menjadi aktif
					DB::table("pengajuan_jadwal")->where("pengajuan_id", $req->pengajuan_id)->update(["status" => "accept"]);

					$company = Company::find($pelanggan->osp_id);
					$emails = $company->admin_email($pelanggan->osp_id);
					Mail::to($emails)->send(new PengajuanJadwalSurveyDisetujui($pelanggan, $tanggalSurvey));
				} else {

					// add teknisi
					$this->addTeknisiSurvey($pelanggan_id, $req->loadTeknisi);
					// perbaruai tanggal pengajuan survey
					DB::table('tanggal_pengajuan_survey')->where('pengajuan_id', $req->pengajuan_id)->delete();
					DB::table("tanggal_pengajuan_survey")->insert($insert);

					// update status pengajuan menjadi pending
					DB::table("pengajuan_jadwal")->where("pengajuan_id", $req->pengajuan_id)->update(["status" => "pending"]);

					// event pemberian jadwal survey
					event(new AddJadwalSurvey($pelanggan_id));

					$companyISP = Company::find($pelanggan->isp_id);
					$companyOSP = Company::find($pelanggan->osp_id);

					// send email
					Mail::to($companyOSP->admin_email($pelanggan->osp_id))->send(new TambahPengajuanJadwalSurvey($pelanggan, $insert, $companyOSP->company_name));
					Mail::to($companyISP->admin_email($pelanggan->isp_id))->send(new TambahPengajuanJadwalSurvey($pelanggan, $insert, $companyISP->company_name));
				}
			} else {
				if (Auth::User()->getRoleName() == 'Admin ISP') {
					// event penentuan jadwal survey oleh ISP (ISP minta reschedule)
					event(new PenentuanJadwalSurvey($pelanggan_id));
				}
				// tambah ke pengajuan jika ada
				$insert = [
					'pelanggan_id' => $pelanggan_id,
					'type' => 'survey',
					'created_at' => date('Y-m-d H:i:s'),
					'created_by' => Auth::User()->id,
					'status' => 'pending',
					'keterangan' => $req->get('keterangan')
				];

				if ($req->get('rekomendasiExist') == 'true') {
					if ($req->get('tgl_rekomendasi') != null && $req->get('tgl_rekomendasi') != '') {
						$insert['tgl_rekomendasi'] = $req->get('tgl_rekomendasi') . " " . $req->get('jam_rekomendasi') . ":00";
					}
				}

				if ($req->get('keterangan') == null && $req->get('alasan_reschedule') !== null) {

					DB::table('pengajuan_jadwal')
						->where('pengajuan_jadwal.pengajuan_id', $data_pengajuan_terakhir->pengajuan_id)
						->update(['alasan_reschedule' => $req->get('alasan_reschedule')]);
				}
				$pengajuan_id = DB::table('pengajuan_jadwal')->insertGetId($insert);

				$insert = [];
				foreach ($req->list as $key => $val) {
					$insert[] = [
						"pengajuan_id" => $pengajuan_id,
						"tgl_survey" => $val["tgl_survey"],
						"jam_survey" => $val["jam_survey"],
						"status" => $val["status"]
					];
				}
				DB::table("tanggal_pengajuan_survey")->insert($insert);


				// send mail
				$company = Company::find($pelanggan->isp_id);
				$emails = $company->admin_email($pelanggan->isp_id);
				Mail::to($emails)->send(new TambahPengajuanJadwalSurvey($pelanggan, $insert, $company->name));
			}

			//create log pengajuan survey
			Auth::user()->createLog(
				trans('pelanggan::logpelanggan.pengajuan_jadwal.pengajuan jadwal'),
				trans('pelanggan::logpelanggan.pengajuan_jadwal.submit pengajuan survey')
			);

			// send response
			$response = [
				'errors' => false,
				'message' => trans('pelanggan::pengajuanjadwal.messages success'),
			];
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();
			dd($th);
			return abort(500, trans('core::core.error 500 title'));
		}

		return $response;
	}

	public function pilihRekomendasiSurvey(Request $request, $pelanggan_id, $pengajuan_id)
	{
		// dd($request->all());
		$pelanggan = Pelanggan::find($pelanggan_id);
		$data = DB::table('pengajuan_jadwal')->where('pengajuan_id', $pengajuan_id)->first();
		$tgl_rekomendasi = $data->tgl_rekomendasi;
		$tanggalSurvey = date('Y-m-d', strtotime($data->tgl_rekomendasi));
		$data = Pelanggan::find($pelanggan_id);
		$saldo = Company::find($data->isp_id)->saldo()->saldo;
		$total_biaya = $data->biaya_otc + $data->biaya_mrc;
		$insert = [
			'pengajuan_id' => $pengajuan_id,
			'tgl_survey' => $tanggalSurvey,
			'jam_survey' => date('H:i:s', strtotime($tgl_rekomendasi)),
			'status' => "not_active",
		];
		if ($saldo - $total_biaya >= 0) {
			$insert['status'] = "active";
			$cek = DB::table("survey")->where("pelanggan_id", $pelanggan_id)->first();

			if ($cek != null) {
				// tambah survey baru && tambah teknisi yang bertugas
				$this->addTeknisiSurvey($pelanggan_id, $request->all());


				// add estimasi so
				DB::table('pelanggan')
					->where('pelanggan_id', $pelanggan_id)
					->update(['status' => 'survey',]);


				// update survey
				DB::table('survey')->where('id', $cek->id)->update(['tgl_survey' => $tanggalSurvey]);

				//create log survey
				Auth::user()->createLog(
					trans('pelanggan::logpelanggan.crud.update.title.survey'),
					trans('pelanggan::logpelanggan.crud.update.desc.survey', ['company' => $this->company_name()])
				);
			} else {

				// tambah survey baru && tambah teknisi yang bertugas
				$this->addTeknisiSurvey($pelanggan_id, $request->all());

				// if ($data->status == 'so') {
				// 	// $estimasi_so = $pelanggan->rangeEstimasiInstalasi(date('Y-m-d H:i:s', strtotime($data->created_at)), date('Y-m-d H:i:s'), 'detik');
				// 	// add estimasi so
				// 	DB::table('pelanggan')
				// 		->where('pelanggan_id', $pelanggan_id)
				// 		->update(['status' => 'survey']);
				// } else {
				// }
				DB::table('pelanggan')
					->where('pelanggan_id', $pelanggan_id)
					->update(['status' => 'survey']);

				//create log survey
				Auth::user()->createLog(
					trans('pelanggan::logpelanggan.crud.create.title.survey'),
					trans('pelanggan::logpelanggan.crud.create.desc.survey', ['company' => $this->company_name()])
				);
			}
			// update status pengajuan menjadi aktif
			DB::table("pengajuan_jadwal")->where("pengajuan_id", $pengajuan_id)->update(["status" => "accept"]);
			// send email
			$emails = (new Company)->admin_email($pelanggan->isp_id);
			Mail::to($emails)->send(new PengajuanJadwalSurveyDisetujui($data, $tanggalSurvey));
		}

		//create log pilih tanggal rekomendasi
		Auth::user()->createLog(
			trans('pelanggan::logpelanggan.pengajuan_jadwal.pengajuan jadwal'),
			trans('pelanggan::logpelanggan.pengajuan_jadwal.rekomendasi survey')
		);

		DB::table('tanggal_pengajuan_survey')->insert($insert);
		return response()->json([
			'errors' => false,
			'message' => trans('pelanggan::pengajuanjadwal.messages success'),
		]);
	}
	public function addTeknisiSurvey($pelanggan_id, $dataTeknisi)
	{
		// DB::beginTransaction();
		//check survey data
		$check = DB::table('survey')
			->where('survey.pelanggan_id', "=", $pelanggan_id);
		$survey = new Survey;
		$tanggalSurvey = null;
		// tambah survey baru
		if ($check->count() == 0) {
			$survey = DB::table('survey')
				->insertGetId([
					'pelanggan_id' => $pelanggan_id,
					'tgl_survey' => $tanggalSurvey,
					'status' => 'active'
				]);

			// tambah teknisi yang bertugas

			foreach ($dataTeknisi as $value) {

				if (gettype($value) == 'integer') {
					DB::table('survey_staff')
						->insert([
							'survey_id' => $survey,
							'user_id' => $value,
						]);
				} elseif (gettype($value) == 'array') {
					if ($value['status']) {
						DB::table('survey_staff')
							->insert([
								'survey_id' => $survey,
								'user_id' => $value['user_id'],
							]);
					}
				}
			}
		} else {
			$survey_id = $check->first()->id;
			$array_teknisi = [];
			if (count($dataTeknisi) > 0) {

				foreach ($dataTeknisi as $value) {
					if (!is_array($value)) {

						$survey->updateTeknisiSurvey($dataTeknisi, $survey_id);
						return response()->json($check->count());
					} else {
						if ($value['status']) {
							array_push($array_teknisi, $value['user_id']);
						}
					}
				}

				$survey->updateTeknisiSurvey($array_teknisi, $survey_id);
			}
		}
		// dd($array_teknisi);
		// DB::commit();
		return response()->json($check->count());
	}
}
