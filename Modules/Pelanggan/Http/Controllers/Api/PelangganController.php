<?php

namespace Modules\Pelanggan\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Pelanggan\Entities\PerubahanHarga;
use Modules\Pelanggan\Entities\Survey;
use Modules\Pelanggan\Entities\Installation;
use Modules\Pelanggan\Entities\Activation;
use Modules\Saldo\Entities\Saldo;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\Company\Entities\PaketForIsp;
use Modules\Pelanggan\Transformers\PelangganTransformers;
use Modules\Pelanggan\Transformers\DetailPelangganTransformer;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Presale\Entities\Presale;
use Modules\Pelanggan\Transformers\SaldoBiayaPelanggan;
use Modules\Pelanggan\Transformers\ListPelangganSelect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Transformers\HistorySLApelanggan;
use Modules\Pelanggan\Transformers\ticketSupportBulanIniTransformer;
use Modules\Ticket\Entities\Ticket;
use Modules\Wilayah\Entities\Wilayah;
use Modules\Analytic\Entities\Analytic;
use Modules\Pelanggan\Events\RemindersProsesTambahPelanggan;
use Modules\User\Entities\Sentinel\User;


class PelangganController extends AdminBaseController
{
	public function company_name()
	{
		$data = Auth::user()->company();
		return $data['name'];
	}

	public function getStatus(Request $req)
	{
		// dd($req->all());
		// return Response()->json(['allow'=>1,'status'=>2]);
		// return Response()->json(['allow' => 1, 'status' => 3]);
		$company 	= Auth::User()->company();
		$presales 	= $req->presales;
		$pelanggan 	= $req->pelanggan;

		if ($presales != 0) {
			// check apakah allow untuk menambahkan pelanggan baru
			$cek = $this->checkAvaliableWilayah($presales, $company->company_id);
			if ($cek['allow'] || $company->type == null) {
				return Response()->json(['allow' => 1, 'status' => 0]);
			} else {
				return Response()->json(['allow' => 0, 'status' => null, 'messages' => $cek['messages']]);
			}
		} else if ($pelanggan != 0) {
			// check apakah saat ngakses pelanggan boleh atau tidak
			$cekStatus = $this->checkIsAllowToAccessPelanggan($company, $pelanggan);
			if ($cekStatus['allow'] || $company->type == null) {
				$cek = Pelanggan::find($pelanggan);
				switch ($cek->status) {
					case 'so':
						$jumlah_pelanggan_saat_ini = Pelanggan::where('isp_id', $cek->isp_id)->where('pelanggan_id', '<=', $cek->isp_id)->count();
						return Response()->json(['allow' => 1, 'status' => 0]);
						break;
					case 'survey':
						return Response()->json(['allow' => 1, 'status' => 1]);
						break;
					case 'instalasi':
						return Response()->json(['allow' => 1, 'status' => 2]);
						break;
					case 'aktivasi':
						return Response()->json(['allow' => 1, 'status' => 3]);
						break;
					default:
						return Response()->json(['allow' => 1, 'status' => 3, 'hide' => true]);
						break;
				}
			} else {
				return Response()->json(['allow' => 0, 'status' => null, 'messages' => $cekStatus['messages']]);
			}
		}
	}

	function checkIsAllowToAccessPelanggan($company, $pelanggan_id)
	{
		$response = ['allow' => false, 'messages' => ''];

		if ($company->type == 'osp') {
			$cek = DB::table('pelanggan')->join('presales', 'presales.presale_id', '=', 'pelanggan.presale_id')
				->join('wilayah', 'presales.wilayah_id', '=', 'wilayah.wilayah_id')
				->where('wilayah.company_id', $company->company_id)->count();
		} else {
			$cek = DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)
				->where('isp_id', $company->company_id)->count();
		}

		if ($cek > 0) {
			$response['allow'] = true;
		} else {
			$response['messages'] = "Anda tidak memiliki akses ke pelanggan ini";
		}
		return $response;
	}

	function checkAvaliableWilayah($presales, $company_id)
	{
		$data = Presale::find($presales)->withDataEndpoint();
		$wilayah = Wilayah::find($data->wilayah_id);
		$cek = DB::table('request_wilayah')->select('request_wilayah.osp_id')
			->where('isp_id', $company_id)
			->where('wilayah_id', $data->wilayah_id)
			->where('status', 'accepted')
			->first();

		$response = ['allow' => false, 'messages' => ''];

		if ($cek != null) {
			$cekAktivePresales = DB::table('active_presales')->where('osp_id', $cek->osp_id)
				->where('presale_id', $presales)
				->count();
			if ($cekAktivePresales > 0) {
				$response['allow'] = true;
			} else {
				$response['messages'] = "Status site " . $data->site_id . " tidak tersedia";
			}
		} else {
			$response['messages'] = "Anda belum  memiliki akses ke site " . $data->site_id . " di wilayah " . $wilayah->name;
		}

		return $response;
	}

	//LIST PELANGGAN
	public function listPelanggan(Request $req)
	{
		$data = Pelanggan::listPelanggan($req);
		return PelangganTransformers::collection($data);
	}

	public function suspendPelanggan(Request $req)
	{
		$pelanggan_id 	= $req->pelanggan_id;
		$alasan 		= $req->alasan;
		$suspend 		= $req->suspend;
		$tgl_suspend	= $req->tgl_suspend;

		if ($suspend == 1) {
			$insert = [
				'pelanggan_id' => $pelanggan_id,
				'alasan' => $alasan,
				'tgl_suspend' => $tgl_suspend,
				'create_at' => date('Y-m-d'),
				'created_by' => Auth::User()->id
			];
			$suspend = DB::table('suspend')->insertGetId($insert);

			Pelanggan::where('pelanggan_id', $pelanggan_id)->update(['suspend_id' => $suspend]);
			$messages = trans('pelanggan::pelanggans.messages.success suspend');
		} else {
			Pelanggan::where('pelanggan_id', $pelanggan_id)->update(['suspend_id' => null]);
			$messages = trans('pelanggan::pelanggans.messages.active suspend');
		}

		return response()->json([
			'errors' => 0,
			'message' => $messages
		]);
	}

	public function detailPelanggan($pelanggan_id)
	{
		$paket = new PaketForIsp;
		$pel = Pelanggan::find($pelanggan_id)->salesOrder();
		$company = Company::find($pel->isp_id);

		$paketWilayah = $paket->getPaketBerlanggananForISP($pel->wilayah_id, $pel->isp_id);
		$osp = DB::table('active_presales')->where('presale_id', $pel->presale_id)->first();

		if ($osp === null) return abort(404, trans('presale::presales.messages.404 active presale'));

		$osp = $osp->osp_id;

		$data = new DetailPelangganTransformer($pel);

		$send = [
			'data' => $data,
			'paket' => $paketWilayah,
			'osp' => $osp
		];

		return Response()->json($send);
	}

	public function submitPelanggan(Request $req)
	{
		$pelanggan = Pelanggan::updateDataPelanggan($req);
		$response = [
			'errors' => false,
			'message' => trans('pelanggan::pelanggans.messages.data updated'),
			'pelanggan' => $pelanggan
		];
		//create log
		Auth::user()->createLog(
			trans('pelanggan::logpelanggan.crud.update.title.pelanggan'),
			trans('pelanggan::logpelanggan.crud.update.desc.pelanggan', ['company' => $this->company_name()])
		);
		return response()->json($response);
	}

	public function getBiayaPaket($paket, Request $req)
	{
		$pelanggan_id = $req->pelanggan_id;
		$paket = PaketBerlangganan::find($paket);
		$diskon = $paket->getAllDiscount();

		$jumlah_pelanggan = 0;
		if ($pelanggan_id != null && $pelanggan_id != "") {
			$pelanggan = Pelanggan::find($pelanggan_id);
			$jumlah_pelanggan = Pelanggan::where('isp_id', $pelanggan->isp_id)->where('pelanggan_id', '<=', $pelanggan_id)->count();
		}

		$hargaPaket = $paket->harga_paket;
		foreach ($diskon as $key => $val) {
			if ($jumlah_pelanggan >= $val->start_pelanggan && $jumlah_pelanggan <= $val->end_pelanggan) {
				$hargaPaket = $hargaPaket - (($hargaPaket * $val->diskon) / 100);
			}
		}

		$biaya_mrc = $hargaPaket;
		$biaya_otc = $paket->biaya_otc;

		return Response()->json([
			'biaya_otc' => $biaya_otc,
			'biaya_mrc' => $biaya_mrc
		]);
	}

	public function detailSaldoPelanggan($pelanggan_id)
	{
		$data = DB::table('saldo_biaya_pelanggan')->where('pelanggan_id', $pelanggan_id)
			->where('settlement', 0)->first();

		if ($data != null) {
			$response = ['show' => 1, 'data' => new SaldoBiayaPelanggan($data)];
		} else {
			$response = ['show' => 0];
		}

		return response()->json($response);
	}

	public function checkSaldoPelanggan($pelanggan_id)
	{
		$pelanggan = Pelanggan::find($pelanggan_id);
		$cek = DB::table('saldo_biaya_pelanggan')
			->where('pelanggan_id', $pelanggan_id)
			->where('settlement', 0)->first();

		// dd($cek);

		$send = [
			'saldo' => Company::find($pelanggan->isp_id)->saldo()->saldo,
			'mrc' => $pelanggan->biaya_mrc,
			'otc' => $pelanggan->biaya_otc,
			'terbayar' => 0,
		];

		if ($cek != null) {
			$data = [
				'total_biaya' => ($pelanggan->biaya_otc + $pelanggan->biaya_mrc),
				'otc' => $pelanggan->biaya_otc,
				'mrc' => $pelanggan->biaya_mrc
			];
			DB::table('saldo_biaya_pelanggan')->where('id', $cek->id)->update($data);
			// dd($cek);
			if (!($cek->saldo_mengendap < $cek->total_biaya)) {
				return response()->json([
					'errors' => false,
					'cukup' => true
				]);
			} else {
				$send['terbayar'] = $cek->saldo_mengendap;
			}
		}


		return response()->json([
			'errors' => false,
			'cukup' => false,
			'data' => $send
		]);
	}

	public function submitSaldoPelanggan($pelanggan_id)
	{
		$data = Pelanggan::find($pelanggan_id);
		$company = Company::find($data->isp_id);
		$saldo = Company::find($data->isp_id)->saldo()->saldo;
		$total_biaya = $data->biaya_otc + $data->biaya_mrc;
		$cek = DB::table('saldo_biaya_pelanggan')->where('pelanggan_id', $pelanggan_id)->where('settlement', 0)->first();

		if ($cek == null) {
			if ($saldo - $total_biaya < 0) {
				return response()->json([
					'errors' => true,
					'message' => 'Saldo tidak mencukupi!'
				]);
			}

			DB::table('saldo_biaya_pelanggan')->insert([
				'pelanggan_id' => $pelanggan_id,
				'total_biaya' => $total_biaya,
				'saldo_mengendap' => $total_biaya,
				'otc' => $data->biaya_otc,
				'mrc' => $data->biaya_mrc
			]);

			Saldo::potong_saldo($data->isp_id, $total_biaya, 'Pembayaran biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan);
			Saldo::tambah_saldo_dibekukan(1, $total_biaya, 'Pembayaran biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan . " dari ISP " . $company->name);

			return response()->json([
				'errors' => false
			]);
		} else {
			$terbayar = $cek->saldo_mengendap;
			$total_biaya = $total_biaya - $terbayar;

			if ($saldo - $total_biaya < 0) {
				return response()->json([
					'errors' => true,
					'message' => 'Saldo tidak mencukupi!'
				]);
			}
			DB::table('saldo_biaya_pelanggan')->where('id', $cek->id)->update([
				'saldo_mengendap' => DB::raw('saldo_mengendap+' . $total_biaya)
			]);

			Saldo::potong_saldo($data->isp_id, $total_biaya, 'Pembayaran kekurangan biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan);
			Saldo::tambah_saldo_dibekukan(1, $total_biaya, 'Pembayaran kekurangan biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan . " dari ISP " . $company->name);

			return response()->json([
				'errors' => false
			]);
		}
	}

	public function checkCardTotalTagihan($pelanggan_id)
	{
		$data = Pelanggan::find($pelanggan_id);
		$company = Company::find($data->isp_id);
		$saldo = $company->saldo()->saldo;
		$total_biaya = $data->biaya_otc + $data->biaya_mrc;
		$cek = DB::table('saldo_biaya_pelanggan')->where('pelanggan_id', $pelanggan_id)->where('settlement', 0)->first();

		if ($cek == null) {
			if ($saldo - $total_biaya < 0) {
				return response()->json([
					'errors' => true,
					'message' => 'Saldo tidak mencukupi!'
				]);
			}

			DB::table('saldo_biaya_pelanggan')->insert([
				'pelanggan_id' => $pelanggan_id,
				'total_biaya' => $total_biaya,
				'saldo_mengendap' => $total_biaya,
				'otc' => $data->biaya_otc,
				'mrc' => $data->biaya_mrc
			]);

			Saldo::potong_saldo($data->isp_id, $total_biaya, 'Pembayaran biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan);
			Saldo::tambah_saldo_dibekukan(1, $total_biaya, 'Pembayaran biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan . " dari ISP " . $company->name);

			return response()->json([
				'errors' => false
			]);
		}

		return response()->json([
			'errors' => false
		]);
	}

	//SUBMIT CANCEL STATUS PELANGGAN
	public function submitCancelStatusStepPelanggan(Request $req)
	{
		DB::beginTransaction();
		$pelanggan = Pelanggan::find($req->pelanggan_id);
		// balikin saldo ke isp,osp,openaccess
		// $pelanggan->pembagianPersentase($req->pelanggan_id);
		if ($pelanggan === null) return abort(404, trans('pelanggan::pelanggans.messages.404'));

		$pelanggan->cancel_proses();

		// update status pelanggan jadi cancel
		$pelanggan->cancel = 1;
		$pelanggan->alasan_cancel = $req->alasan_cancel;
		$pelanggan->save();

		DB::commit();
		$response = [
			'error' => false,
			'message' => trans('pelanggan::pelanggans.messages.cancel pelanggan', ['nama_pelanggan' => $pelanggan->nama_pelanggan])
		];
		return response()->json($response);
	}

	//SUBMIT ACTIVATE STATUS PELANGGAN
	public function submitActivateStatusStepPelanggan(Request $req)
	{
		$pelanggan = new Pelanggan;
		$response = $pelanggan->activateStep($req->pelanggan_id);
		return response()->json($response);
	}

	public function getAllPelanganForSelect(Request $req)
	{
		$ListPelangganAll = DB::table('pelanggan')->select(['pelanggan_id', 'nama_pelanggan', 'pelanggan.biaya_mrc', 'paket_berlangganan.nama_paket'])
			->join('paket_berlangganan', 'paket_berlangganan.paket_id', '=', 'pelanggan.paket_id')
			->whereNull('suspend_id');

		switch ($req->type) {
			case 'pelanggan aktif':
				$ListPelangganAll->where('pelanggan.status', 'aktif');
				break;
			case 'sales order':
				$ListPelangganAll->where('pelanggan.status', 'so');
				break;
			case 'survey':
				$ListPelangganAll->where('pelanggan.status', 'survey');
				break;
			case 'instalasi':
				$ListPelangganAll->where('pelanggan.status', 'instalasi');
				break;
			case 'aktivasi':
				$ListPelangganAll->where('pelanggan.status', 'aktivasi');
				break;
			default:
				break;
		}

		$ListPelangganAll = $ListPelangganAll->where('isp_id', $req->company_id)->get();

		return response()->json(ListPelangganSelect::collection($ListPelangganAll));
	}

	public function getPelanganOpenSuspendForSelect(Request $req)
	{
		$ListPelangganAll = DB::table('pelanggan')->select(['pelanggan.pelanggan_id', 'nama_pelanggan', 'pelanggan.biaya_mrc', 'paket_berlangganan.nama_paket', 't.ticket_id', 'u.*'])
			->join('ticket_support_suspend as t', 't.pelanggan_id', '=', 'pelanggan.pelanggan_id')
			->join('ticket_support as u', 'u.ticket_id', '=', 't.ticket_id')
			->join('paket_berlangganan', 'paket_berlangganan.paket_id', '=', 'pelanggan.paket_id')
			->where('u.status', '!=', 'closed')
			->whereNull('suspend_id');
		switch ($req->type) {
			case 'pelanggan aktif':
				$ListPelangganAll->where('pelanggan.status', 'aktif');
				break;
			case 'sales order':
				$ListPelangganAll->where('pelanggan.status', 'so');
				break;
			case 'survey':
				$ListPelangganAll->where('pelanggan.status', 'survey');
				break;
			case 'instalasi':
				$ListPelangganAll->where('pelanggan.status', 'instalasi');
				break;
			case 'aktivasi':
				$ListPelangganAll->where('pelanggan.status', 'aktivasi');
				break;
			default:
				break;
		}

		$ListPelangganAll = $ListPelangganAll->where('isp_id', $req->company_id)->get();
		return response()->json(ListPelangganSelect::collection($ListPelangganAll));
	}

	public function jumlahList()
	{
		$company = Auth::User()->company();
		$pelanggan = Pelanggan::whereNull('pelanggan.deleted_at')->select('pelanggan.status')
			->where($company->type . '_id', '=', $company->company_id)
			->where('pelanggan.cancel', '!=', 1)
			->join('presales', 'presales.presale_id', '=', 'pelanggan.presale_id')
			->join('wilayah', 'wilayah.wilayah_id', '=', 'presales.wilayah_id')
			->join('paket_berlangganan', 'paket_berlangganan.paket_id', '=', 'pelanggan.paket_id')
			->join('companies as isp', 'isp.company_id', '=', 'pelanggan.isp_id')
			->join('companies as osp', 'osp.company_id', '=', 'pelanggan.osp_id')
			->get();

		$jumlah = [];
		foreach ($pelanggan as $key => $value) {
			$jumlah[] = $value->status;
		}

		$data = array_count_values($jumlah);
		return response()->json($data);
	}


	public static function getHistorySLApelanggan($id)
	{
		$tiket = new Ticket();
		$pelanggan = new Pelanggan();
		$slaBulanIni = $tiket->getTicketSupportSlaPelangganBulanIni();
		$dataPelanggan = [];
		$KoneksiMati = [];
		$totalKoneksiMati = 0;
		$namaPaket = DB::table('pelanggan')
			->join('paket_berlangganan', 'pelanggan.paket_id', '=', 'paket_berlangganan.paket_id')
			->where('pelanggan.pelanggan_id', '=', $id)
			->select('paket_berlangganan.paket_id')
			->first();
		// mengeluarkan data sla Bulan Ini
		foreach ($slaBulanIni as $key => $value) {
			if ($value->pelanggan_id == $id) {
				$dataPelanggan = (object) $value;
			}
		}


		// mengambil selisih start gangguan dan end gangguan (menit)
		foreach ($dataPelanggan->jam as $jam) {
			$jumlah = $pelanggan->rangeEstimasiInstalasi($jam['start_gangguan'], $jam['end_gangguan'], 'Menit');
			array_push($KoneksiMati, (object)$jumlah);
		}

		foreach ($KoneksiMati as $total) {
			$totalKoneksiMati = $totalKoneksiMati + $total->total_menit;
		}

		$persentaseKoneksiMati = $totalKoneksiMati / 43800 * 100;
		$persentaseToleransi = 100 - $dataPelanggan->sla;
		$totalPotongan = $persentaseKoneksiMati * $dataPelanggan->biaya_mrc / 100;
		// dd($dataPelanggan->biaya_mrc - $totalPotongan);
		$data = [
			'pelanggan_id' => $dataPelanggan->pelanggan_id,
			'paket_id' =>  $namaPaket->paket_id,
			'biaya_mrc' =>  $dataPelanggan->biaya_mrc,
			'sla_paket' =>  $dataPelanggan->sla,
			'total_koneksi_mati' =>  $totalKoneksiMati,
			'persentase_total_koneksi_mati' =>  $persentaseKoneksiMati,
			'persentase_toleransi' =>  $persentaseToleransi,
			'total_pengurangan_tagihan' => floor($totalPotongan),
		];

		$history = DB::table('history_pelanggan_sla');
		$cek = $history
			->where('history_pelanggan_sla.pelanggan_id', '=', $id)
			->where(function ($query) {
				$query
					->where('history_pelanggan_sla.created_at', 'LIKE', date('Y-m') . "%");
			})
			->count();

		if ($cek > 0) {
			$data['updated_at'] = date('Y-m-d H:i:s');
			DB::table('history_pelanggan_sla')->update($data);
		} else {
			$data['created_at'] = date('Y-m-d H:i:s');
			DB::table('history_pelanggan_sla')->insert($data);
		}

		return $slaBulanIni;
	}

	public function dataHistorySla(Request $request, $pelanggan_id)
	{
		$search = $request->get('search');
		$data =	DB::table('history_pelanggan_sla')
			->join('pelanggan', 'history_pelanggan_sla.pelanggan_id', '=', 'pelanggan.pelanggan_id')
			->join('paket_berlangganan', 'history_pelanggan_sla.paket_id', '=', 'paket_berlangganan.paket_id')
			->where('history_pelanggan_sla.pelanggan_id', '=', $pelanggan_id);
		if ($request->get('search') != null) {
			$data = $data
				->where(function ($query) use ($search) {
					$query->where('paket_berlangganan.nama_paket', 'LIKE', "%{$search}%")
						->orWhere('history_pelanggan_sla.sla_paket', 'LIKE', "%{$search}%")
						->orWhere('history_pelanggan_sla.biaya_mrc', 'LIKE', "%{$search}%")
						->orWhere('history_pelanggan_sla.persentase_total_koneksi_mati', 'LIKE', "%{$search}%")
						->orWhere('history_pelanggan_sla.total_pengurangan_tagihan', 'LIKE', "%{$search}%")
						->orWhere('history_pelanggan_sla.created_at', 'LIKE', date('Y-m', strtotime($search)) . "%");
				});
		}
		$data	 =	$data
			->select(['history_pelanggan_sla.*', 'paket_berlangganan.nama_paket', 'pelanggan.nama_pelanggan'])
			->paginate($request->get('per_page', 10));
		return HistorySLApelanggan::collection($data);
	}

	public function ticketSupportBulanIni(Request $request, $id)
	{
		$bulan = $request->bulan;

		if ($bulan == null) {
			$bulan = date('M-Y');
		}
		$data = DB::table('ticket_support_sla')
			->select('ticket_support_sla.*')
			->where('ticket_support_sla.pelanggan_id', '=', $id)
			->whereNotIn('ticket_support_sla.accept_isp_by', function ($query) {
				$query
					->select('ticket_support_sla.accept_isp_by')
					->where('ticket_support_sla.accept_isp_by', '=', null);
			})
			->whereNotIn('ticket_support_sla.accept_osp_by', function ($query) {
				$query
					->select('ticket_support_sla.accept_osp_by')
					->where('ticket_support_sla.accept_osp_by', '=', null);
			})
			->where(function ($query) use ($bulan) {
				$query->where('ticket_support_sla.start_gangguan', 'LIKE', date('Y-m', strtotime($bulan)) . "%");
			})
			->paginate($request->get('per_page', 10));
		return ticketSupportBulanIniTransformer::collection($data);
	}

	public function getPemakaianBulanini($pelanggan_id)
	{

		$pemakaian = 100;
		$check = DB::table('history_pelanggan_sla')
			->where(function ($query) {
				$query->where('history_pelanggan_sla.created_at', 'LIKE', date('Y-m') . "%");
			})
			->where('history_pelanggan_sla.pelanggan_id', '=', $pelanggan_id)
			->select(['history_pelanggan_sla.persentase_total_koneksi_mati', 'history_pelanggan_sla.created_at'])
			->first();
		if ($check == null) {
			$check =  0;
			return $pemakaian;
		}
		$pemakaian -= floor($check->persentase_total_koneksi_mati);

		return $pemakaian;
	}

	public function delete_pelanggan(Pelanggan $pelanggan)
	{
		$nama_pelanggan = $pelanggan->nama_pelanggan;
		$pelanggan->delete();

		return response()->json([
			'errors' => false,
			'message' => trans('pelanggan::pelanggans.messages.success delete pelanggan', [
				'nama_pelanggan' => $nama_pelanggan
			])
		]);
	}

	public function getTimerOSP($pelanggan)
	{
		$data = Analytic::getLastActivityAndTimerOSP($pelanggan);

		return response()->json($data);
	}

	public function getInfoStepPelanggan($pelanggan)
	{
		$send = [
			'show' => false,
			'type' => 'warning|info',
			'messages' => null
		];

		$type_company = Auth::User()->company()->type;
		if ($type_company != null) {
			$data = Pelanggan::find($pelanggan);
			// KONDISI PERUBAHAN HARGA
			$perubahan = PerubahanHarga::where('pelanggan_id', $pelanggan)->where('status', 'request')
				->orderBy('perubahan_harga_id', 'desc')->first();
			if ($perubahan != null) {
				$send['show'] = true;
				$type = User::find($perubahan->created_by)->company()->type;
				if ($type_company == 'osp') {
					if ($type == 'isp') {
						$send['type'] = 'warning';
						$send['messages'] = 'Harap melakukan konfirmasi perubahan harga dari ISP';
					} else {
						$send['type'] = 'info';
						$send['messages'] = 'Menunggu konfirmasi Perubahan harga dari ISP';
					}
				} else {
					if ($type == 'isp') {
						$send['type'] = 'warning';
						$send['messages'] = 'Harap melakukan konfirmasi perubahan harga dari OSP';
					} else {
						$send['type'] = 'info';
						$send['messages'] = 'Menunggu konfirmasi Perubahan harga dari OSP';
					}
				}
			} else if ($data->cancel == 0) {

				switch ($data->status) {

						// KONDISI SALES ORDER
					case 'so':
						$pengajuan = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan)
							->where('type', 'survey')->where('status', 'pending')->first();

						if ($pengajuan != null) {
							$count = DB::table('tanggal_pengajuan_survey')->where('pengajuan_id', $pengajuan->pengajuan_id)->count();
							// dd($count);

							if ($type_company == 'osp') {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap masukkan jadwal survey';
								} else {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu konfirmasi jadwal survey dari ISP';
								}
							} else {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu jadwal survey dari OSP';
								} else {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap memilih jadwal survey';
								}
							}
						}

						break;

						// KONDISI SURVEY
					case 'survey':
						// cek pengajuan jadwal survey
						$pengajuan = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan)
							->where('type', 'survey')->where('status', 'pending')->first();
						if ($pengajuan != null) {
							$count = DB::table('tanggal_pengajuan_survey')->where('pengajuan_id', $pengajuan->pengajuan_id)->count();
							if ($type_company == 'osp') {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap masukkan jadwal survey';
								} else {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu konfirmasi jadwal survey dari ISP';
								}
							} else {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu jadwal survey dari OSP';
								} else {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap memilih jadwal survey';
								}
							}
							break;
						}

						// cek udah finish atau belum
						$survey = Survey::where('pelanggan_id', $pelanggan)->where('tgl_survey', 'LIKE', '%' . date('Y-m-d') . '%')
							->where('status', 'active')->first();
						if ($survey != null) {
							$send['show'] = true;
							if ($type_company == 'osp') {
								$send['type'] = 'info';
								$send['messages'] = 'Harap untuk memasukkan hasil survey setelah survey dilakukan';
							} else {
								$send['type'] = 'info';
								$send['messages'] = 'Menunggu hasil survey dari OSP';
							}
							break;
						}

						// cek pengajuan jadwal instalasi
						$pengajuan = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan)
							->where('type', 'instalasi')->where('status', 'pending')->first();
						if ($pengajuan != null) {
							$count = DB::table('tanggal_pengajuan_instalasi')->where('pengajuan_id', $pengajuan->pengajuan_id)->count();
							if ($type_company == 'osp') {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap masukkan jadwal instalasi';
								} else {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu konfirmasi jadwal instalasi dari ISP';
								}
							} else {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu jadwal instalasi dari OSP';
								} else {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap memilih jadwal instalasi';
								}
							}
							break;
						}
						break;

						// KONDISI INSTALASI
					case 'instalasi':
						// cek pengajuan jadwal instalasi

						//sebelum
						$pengajuan = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan)
							->where('type', 'instalasi')->where('status', 'pending')->first();

						// //sesudah
						// $pengajuan = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan)
						// 	->where('type', 'instalasi')->orderBy('pengajuan_id','desc')->first();

						if ($pengajuan != null) {
							$count = DB::table('tanggal_pengajuan_instalasi')->where('pengajuan_id', $pengajuan->pengajuan_id)->count();
							if ($type_company == 'osp') {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap masukkan jadwal instalasi';
								} else {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu konfirmasi jadwal instalasi dari ISP';
								}
							} else {
								$send['show'] = true;
								if ($count == 0) {
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu jadwal instalasi dari OSP';
								} else {
									$send['type'] = 'warning';
									$send['messages'] = 'Harap memilih jadwal instalasi';
								}
							}
							break;
						}

						// cek udah finish atau belum
						$instalasi = Installation::where('pelanggan_id', $pelanggan)->first();
						if ($instalasi != null) {
							if ($instalasi->status == 'active') {
								$strtotime1 = strtotime(date('Y-m-d', strtotime($instalasi->tgl_instalasi)));
								$strtotime2 = strtotime(date('Y-m-d'));
								if ($type_company == 'osp' && $strtotime2 >= $strtotime1) {
									$send['show'] = true;
									$send['type'] = 'warning';
									$send['messages'] = 'Harap untuk memasukkan hasil instalasi setelah instalasi dilakukan';
								} else {
									$send['show'] = true;
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu hasil instalasi dari OSP';
								}
							}
							break;
						}

						// KONDISI AKTIVASI
					case 'aktivasi':
						$aktivasi = Activation::where('pelanggan_id', $pelanggan)->first();
						if ($aktivasi != null) {
							if ($aktivasi->status == 'proses' || $aktivasi->status == 'unapprove') {
								$strtotime1 = strtotime(date('Y-m-d', strtotime($aktivasi->tgl_aktivasi)));
								$strtotime2 = strtotime(date('Y-m-d'));
								if ($type_company == 'osp' && $strtotime2 >= $strtotime1) {
									$send['show'] = true;
									$send['type'] = 'warning';
									$send['messages'] = 'Harap untuk memasukkan hasil aktivasi setelah aktivasi dilakukan';
								} else if ($type_company == 'isp') {
									$send['show'] = true;
									$send['type'] = 'info';
									$send['messages'] = 'Menunggu hasil aktivasi dari OSP';
								}
							}
						} else {
							$send['show'] = true;
							if ($type_company == 'osp') {
								$send['type'] = 'warning';
								$send['messages'] = 'Harap untuk Menentukan tanggal aktivasi';
							} else if ($type_company == 'isp') {
								$send['type'] = 'info';
								$send['messages'] = 'Menunggu hasil aktivasi dari OSP';
							}
						}
						break;

					default:
						# code...
						break;
				}
			}
		}

		return response()->json($send);
	}
	public function reminders()
	{
		event($event = new RemindersProsesTambahPelanggan());

		return response()->json([
			'errors' => false
		]);
	}

	public function rating(Pelanggan $pelanggan)
	{
		return $pelanggan->rating();
	}
}
