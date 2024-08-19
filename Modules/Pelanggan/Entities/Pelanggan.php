<?php

namespace Modules\Pelanggan\Entities;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Presale\Entities\Presale;
use Modules\Company\Entities\Company;
use Modules\Invoice\Entities\Invoice;
use Modules\Saldo\Entities\Saldo;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Pelanggan\Entities\Survey;
use Modules\Pelanggan\Transformers\TglPengajuanInstalasi;
use Modules\Pelanggan\Transformers\TglPengajuanSurvey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Configuration\Entities\Configuration;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Company\Entities\PaketBerlangganan;
use Modules\Core\Traits\Rating;
use Modules\Invoice\Entities\InvoiceItem;
use Modules\Wilayah\Entities\Pengajuan;

class Pelanggan extends Model
{
	use SoftDeletes,  ConvertToCurrency, Rating;

	protected $table = 'pelanggan';
	protected $dates = ['deleted_at'];
	protected $primaryKey = "pelanggan_id";
	protected $fillable = [
		'pelanggan_id',
		'kode_pelanggan',
		'presale_id',
		'isp_id',
		'osp_id',
		'paket_id',
		'biaya_mrc',
		'biaya_otc',
		'nama_pelanggan',
		'telepon',
		'email',
		'status_kepemilikan',
		'jenis_identitas',
		'nomor_identitas',
		'foto_identitas',
		'status',
		'created_at',
		'created_by',
		'updated_at',
		'updated_by',
		'deleted_at',
		'alasan_cancel',
		'foto_jalur_ep',
		'wilayah_id',
		'estimasi_instalasi',
		'tipe_ep'
	];
	public $translatedAttributes = [];

	public function rangeEstimasiInstalasi($start, $end, $type)
	{
		$startTime = Carbon::parse($start);
		$endTime = Carbon::parse($end);
		$totalDuration =  $startTime->diff($endTime);
		$tahun = $totalDuration->y;
		$bulan = $totalDuration->m;
		$hari = $totalDuration->d;
		$jam = $totalDuration->h;
		$menit = $totalDuration->i;
		$detik = $totalDuration->s;

		$data = [];
		if ($type == 'Hari') {
			$tahun = $tahun * 365;
			$bulan = $bulan * 30;
			$hari = $hari + $tahun + $bulan;
			return $data = [
				'total_hari' => $hari,
				'jam' => $jam,
				'menit' => $menit,
				'detik' => $detik,
			];
		}
		if ($type == 'Jam') {
			$tahun = $tahun * 8760;
			$bulan = $bulan * 720;
			$hari = $hari * 24;
			$jam = $jam + $hari + $tahun + $bulan;

			return $data = [
				'total_jam' => $jam,
				'menit' => $menit,
				'detik' => $detik,
			];
		}
		if ($type == 'Menit') {
			$tahun = $tahun * 525600;
			$bulan = $bulan * 43800;
			$hari = $hari * 1440;
			$jam = $jam * 60;
			$menit = $jam + $hari + $tahun + $bulan;

			return $data = [
				'total_menit' => $menit,
				'detik' => $detik,
			];
		}
		if ($type == 'detik') {
			$hari = $hari * 86400;
			$jam = $jam * 3600;
			$menit = $menit * 60;
			$detik = $detik + $hari + $jam + $menit;

			return $data = [
				'total_detik' => $detik
			];
		}
		return false;
	}

	// Add kode_pelanggan
	public function KodePelanggan()
	{
		$date = substr(date('Ym'), 2);
		$code = 'P';
		$countDataOnTable = DB::table('pelanggan')->where('kode_pelanggan', 'LIKE', '%' . $code . $date . '%')->count();
		$data = $countDataOnTable + 1;
		$kodePelanggan = $code .= $date .= str_pad($data, 5, '0', STR_PAD_LEFT);
		// dd($code);
		return $kodePelanggan;
	}


	public function salesOrder()
	{
		$data = $this->withDataPresales();
		$survey = DB::table('survey')->where('pelanggan_id', $this->pelanggan_id)->first();
		if ($survey != null) {
			$data->tgl_survey = $survey->tgl_survey;
		} else {
			$data->tgl_survey = null;
		}
		return $data;
	}

	public function withDataPresales()
	{
		$data = Presale::find($this->presale_id)->withDataEndpoint();
		$this->site_id = $data->site_id;
		$this->lat = $data->lat;
		$this->lon = $data->lon;
		$this->address = $data->address;
		$this->foto_rumah = $data->foto_rumah;
		$this->icon = $data->icon;
		$this->lat_ep = $data->lat_ep;
		$this->lon_ep = $data->lon_ep;
		$this->endpoint_name = $data->endpoint_name;
		$this->type_ep = $data->type_ep;
		$this->icon_ep = $data->icon_ep;
		$this->wilayah_id = $data->wilayah_id;
		$this->path = $data->path;

		return $this;
	}

	public function loadPengajuan($pelanggan_id, $type)
	{
		$data = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan_id)
			->where('type', $type)->get();
		$alasan = null;
		foreach ($data as $key => $val) {
			$list = DB::table('tanggal_pengajuan_' . $type)
				->where('pengajuan_id', $val->pengajuan_id);
			if ($type == 'instalasi') {
				$list = $list->join('slot_instalasi', 'slot_instalasi.slot_id', '=', 'tanggal_pengajuan_instalasi.slot_id')->get();
				$list = TglPengajuanInstalasi::collection($list);
			} else {
				$list = TglPengajuanSurvey::collection($list->get());
			}
			if ($key !== 0) {
				$alasan = $data[$key - 1]->alasan_reschedule;
			}
			$val->alasan_reschedue_terakhir = $alasan;
			$val->list = $list;
		}
		return $data;
	}

	//STEP CANCEL STATUS TABEL PELANGGAN
	public function pembagianPersentase($pelanggan_id)
	{

		$update_saldo = new Saldo;
		$survey = new Survey;
		$config = Configuration::find(1);
		$pelanggan = DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id);

		$getDataPelanggan = $pelanggan->first();
		$isp_id = $getDataPelanggan->isp_id;
		$osp_id = $getDataPelanggan->osp_id;
		$saldo = DB::table('saldo_biaya_pelanggan')->where('pelanggan_id', $pelanggan_id)->where('settlement', 0);

		$getSaldoPelanggan = $saldo->first();
		if ($getSaldoPelanggan != null) {
			$mrc = $getSaldoPelanggan->mrc;
			$otc = $getSaldoPelanggan->otc;
			$totalTagihan = $getSaldoPelanggan->total_biaya;
			$saldoMengendap = $getSaldoPelanggan->saldo_mengendap;
			$sisa = $getSaldoPelanggan->saldo_mengendap - $mrc;
			if ($sisa < 0) {
				$totalTagihan = $saldoMengendap;
				$saldo->update(['total_biaya' => $totalTagihan]);
				$sisa = 0;
			}
			$refundOpenaccess = $totalTagihan * $config->persentase_refund_openaccess / 100;
			$refundOSP = $totalTagihan * $config->persentase_refund_osp / 100;
			if ($getDataPelanggan && $getSaldoPelanggan) {

				$prosesRefund = [
					'persentaseOSP' => $config->persentase_refund_osp,
					'persentaseOpenaccess' =>  $config->persentase_refund_openaccess,
					'refundOSP' => $refundOSP,
					'refundOpenaccess' => $refundOpenaccess,
					'totalTagihan' => $totalTagihan,
					'saldoMengendap' => $saldoMengendap,
					'sisa' => $sisa,
				];
				if ($getDataPelanggan->status == 'survey') {
					$survey->cancelSurvey($pelanggan_id, $osp_id, $isp_id, $prosesRefund);
				} else if ($getDataPelanggan->status == 'instalasi' || $getDataPelanggan->status == 'aktivasi') {
					$ke_isp = $sisa;
					$persentaseOSP = 100 - $config->persentase_refund_openaccess;
					$refundOSP = round($totalTagihan * $persentaseOSP / 100);

					$update_saldo->potong_saldo_dibekukan(1, $totalTagihan, trans('pelanggan::pelangganinstalasi.controller.terjadi potong beku'));
					$update_saldo->potong_saldo(1, $refundOSP, trans('pelanggan::pelangganinstalasi.controller.terjadi pemotongan saldo'));

					$saldo->update([
						'biaya_admin' => $refundOpenaccess,
						'persentase_biaya_admin' => $config->persentase_refund_openaccess,
						'biaya_osp' => $refundOSP,
						'persentase_biaya_osp' => $persentaseOSP,
						'pengembalian_isp' => $sisa,
						'settlement' => 1
					]);


					//saldo ke openaccess
					// $update_saldo->tambah_saldo(1, $ke_openaccess, trans('pelanggan::pelangganinstalasi.controller.saldo openaccess'));

					//saldo ke osp
					$update_saldo->tambah_saldo($osp_id, $refundOSP, trans('pelanggan::pelangganinstalasi.controller.saldo osp'));

					//saldo ke isp
					if ($sisa != 0) {
						$update_saldo->tambah_saldo($isp_id, $sisa, trans('pelanggan::pelangganinstalasi.controller.saldo isp'));
					}
				}
			}
		}
	}

	//STEP ACTIVATE STATUS TABEL PELANGGAN
	public function activateStep($pelanggan_id)
	{
		try {

			$pelanggan = DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)->first();

			if ($pelanggan == null) return abort(404, trans('pelanggan::pelanggans.messages.404'));

			$status = $pelanggan->status;
			DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)->update([
				'cancel' => '0',
				'alasan_cancel' => null
			]);

			//Update data saldo biaya pelanggan
			SaldoBiayaPelanggan::updateOrCreate([
				'pelanggan_id' => $pelanggan_id,
				'settlement' => 0
			], [
				'total_biaya' => (int)$pelanggan->biaya_mrc + (int)$pelanggan->biaya_otc,
				'mrc' => $pelanggan->biaya_mrc,
				'otc' => $pelanggan->biaya_otc
			]);

			if ($status == 'survey') {
				DB::table('survey')->where('pelanggan_id', '=', $pelanggan_id)->update(['status' => 'active']);
			}

			if ($status == 'instalasi') {
				DB::table('instalasi')->where('pelanggan_id', '=', $pelanggan_id)->update(['status' => 'active']);
			}

			// $saldo->tambah_saldo(1,$totalTagihan,'Terjadi pembatalan pada tahap instalasi');
			// 	$saldo->tambah_saldo_dibekukan(1,$totalTagihan,'Terjadi pembatalan pada tahap instalasi');

			$respond = [
				'error' => false,
				'message' => 'Activate Successfully'
			];
		} catch (\Exception $e) {
			dd($e);
		}

		return $respond;
	}

	public static function totalPelangganByStatus(String $status)
	{

		$pelanggan = self::where('status', $status)->whereNull('suspend_id');

		if (Auth::user()->hasRoleName('Admin ISP')) {
			$pelanggan->where('isp_id', Auth::user()->userCompanies->company_id);
		}

		if (Auth::user()->hasRoleName('Admin OSP')) {
			$pelanggan->where('osp_id', Auth::user()->userCompanies->company_id);
		}

		return $pelanggan->count();
	}

	public static function totalTagihanBulanan()
	{
		$pelanggan = self::select([
			'paket_berlangganan.harga_paket'
		])
			->where('isp_id', Auth::user()->userCompanies->company_id)
			->join('paket_berlangganan', 'paket_berlangganan.paket_id', 'pelanggan.paket_id')
			->where('pelanggan.status', 'aktif')
			->whereNull('pelanggan.suspend_id')
			->get()
			->toArray();

		$total = array_sum(array_map(function ($pelanggan) {
			return $pelanggan['harga_paket'];
		}, $pelanggan));

		return $total;
	}

	public static function updateDataPelanggan($req)
	{
		$user        = Auth::User();
		$company_user = $user->company();
		// update ke pelanggan
		$update = [
			'paket_id' => $req->get('paket_id'),
			'biaya_mrc' => $req->get('biaya_mrc'),
			'biaya_otc' => $req->get('biaya_otc'),
			'nama_pelanggan' => $req->get('nama_pelanggan'),
			'telepon' => $req->get('telepon'),
			'email' => $req->get('email'),
			'jenis_identitas' => $req->get('jenis_identitas'),
			'nomor_identitas' => $req->get('nomor_identitas'),
			'status_kepemilikan' => $req->get('status_kepemilikan'),
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $user->id,
		];

		if ($req->file('foto_identitas') != null) {
			$update['foto_identitas'] = ImageController::uploadFoto($req->file('foto_identitas'));
		}
		if ($req->file('foto_jalur_ep') != null) {
			$update['foto_jalur_ep'] = ImageController::uploadFoto($req->file('foto_jalur_ep'));
		}

		// update data pelanggan sesuai dengan data update diatas
		$update = DB::table('pelanggan')->where('pelanggan_id', $req->get('pelanggan_id'))->update($update);
		return $req->get('pelanggan_id');
	}

	public static function listPelanggan(Request $req)
	{
		$company_id = $req->company_id;

		// data select
		$select = [
			'pelanggan.pelanggan_id',
			'pelanggan.biaya_mrc',
			'pelanggan.nama_pelanggan',
			'pelanggan.telepon',
			'pelanggan.alasan_cancel',
			'email',
			'presales.site_id',
			'presales.address',
			'paket_berlangganan.nama_paket',
			'pelanggan.status',
			'pelanggan.cancel',
			'suspend.alasan as alasan_suspend',
			'isp.name as isp_name',
			'osp.name as osp_name',
			'wilayah.name as wilayah_name',
		];

		// default query
		$ListPelangganAll = DB::table('pelanggan')
			->leftJoin('suspend', 'suspend.suspend_id', '=', 'pelanggan.suspend_id')
			->join('presales', 'presales.presale_id', '=', 'pelanggan.presale_id')
			->join('wilayah', 'wilayah.wilayah_id', '=', 'presales.wilayah_id')
			->join('paket_berlangganan', 'paket_berlangganan.paket_id', '=', 'pelanggan.paket_id')
			->join('companies as isp', 'isp.company_id', '=', 'pelanggan.isp_id')
			->join('companies as osp', 'osp.company_id', '=', 'pelanggan.osp_id');


		// TYPE
		switch ($req->type) {
			case 'pelanggan aktif':
				$ListPelangganAll->where('pelanggan.status', 'aktif');
				if ($req->status == 'suspend') {
					$ListPelangganAll->whereNotNull('pelanggan.suspend_id');
				} else {
					$ListPelangganAll->whereNull('pelanggan.suspend_id');
				}
				break;
			case 'sales order':
				$select[] = DB::raw('(select count(a.id) from tanggal_pengajuan_survey as a join pengajuan_jadwal as b on b.pengajuan_id = a.pengajuan_id where b.pelanggan_id = pelanggan.pelanggan_id) as jumlah_jadwal');
				$ListPelangganAll->where('pelanggan.status', 'so')
					->orderBy('jumlah_jadwal', 'asc');

				if ($req->status == 'cancel') {
					$ListPelangganAll->where('cancel', 1);
				} else if ($req->status == 'active') {
					$ListPelangganAll->where('cancel', 0);
					$ListPelangganAll->where(DB::raw('(select count(a.id) from tanggal_pengajuan_survey as a join pengajuan_jadwal as b on b.pengajuan_id = a.pengajuan_id where b.pelanggan_id = pelanggan.pelanggan_id)'), '>', 0);
				} else if ($req->status == 'new') {
					$ListPelangganAll->where('cancel', 0);
					$ListPelangganAll->where(DB::raw('(select count(a.id) from tanggal_pengajuan_survey as a join pengajuan_jadwal as b on b.pengajuan_id = a.pengajuan_id where b.pelanggan_id = pelanggan.pelanggan_id)'), '=', 0);
				} else if ($req->status == 'suspend') {
					$ListPelangganAll->whereNotNull('pelanggan.suspend_id');
				} else if ($req->status == 'aktif') {
					$ListPelangganAll->where('pelanggan.cancel', 0);
				}

				break;
			case 'survey':
				$select[] = 'survey.status as status_survey';
				$select[] = 'perubahan_harga.status as status_perubahan_harga';
				$select[] = DB::raw('(select count(*) from pengajuan_jadwal where pengajuan_jadwal.pelanggan_id = pelanggan.pelanggan_id and pengajuan_jadwal.status = "pending" and type = "survey") as pengajuan_jadwal_survey_pending');
				$ListPelangganAll->join('survey', 'survey.pelanggan_id', 'pelanggan.pelanggan_id')
					->leftjoin('perubahan_harga', 'perubahan_harga.pelanggan_id', 'pelanggan.pelanggan_id');


				if ($req->status == 'cancel') {
					$ListPelangganAll->where('pelanggan.cancel', 1);
				} else if ($req->status == 'active') {
					$ListPelangganAll->where('survey.status', 'active');
				} else if ($req->status == 'new') {
					$ListPelangganAll->whereNull('survey.status');
				} else if ($req->status == 'suspend') {
					$ListPelangganAll->whereNotNull('pelanggan.suspend_id');
				} else if ($req->status == 'aktif') {
					$ListPelangganAll->where('pelanggan.cancel', 0);
				}
				$ListPelangganAll->where('pelanggan.status', 'survey');
				break;
			case 'instalasi':
				$select[] = 'instalasi.status as status_instalasi';
				$select[] = 'perubahan_harga.status as status_perubahan_harga';
				$select[] = DB::raw('(select count(*) from pengajuan_jadwal where pengajuan_jadwal.pelanggan_id = pelanggan.pelanggan_id and pengajuan_jadwal.status = "pending" and type = "instalasi") as pengajuan_jadwal_instalasi_pending');
				$ListPelangganAll->join('instalasi', 'instalasi.pelanggan_id', 'pelanggan.pelanggan_id')
					->leftjoin('perubahan_harga', 'perubahan_harga.pelanggan_id', 'pelanggan.pelanggan_id');

				if ($req->status == 'cancel') {
					$ListPelangganAll->where('pelanggan.cancel', 1);
				} else if ($req->status == 'active') {
					$ListPelangganAll->where('instalasi.status', 'active');
				} else if ($req->status == 'new') {
					$ListPelangganAll->whereNull('instalasi.status');
				} else if ($req->status == 'suspend') {
					$ListPelangganAll->whereNotNull('pelanggan.suspend_id');
				} else if ($req->status == 'aktif') {
					$ListPelangganAll->where('pelanggan.cancel', 0);
				}
				$ListPelangganAll->where('pelanggan.status', 'instalasi');
				break;
			case 'aktivasi':
				$select[] = 'aktivasi.status as status_aktivasi';
				$ListPelangganAll->leftJoin('aktivasi', 'aktivasi.pelanggan_id', 'pelanggan.pelanggan_id');
				if ($req->status == 'cancel') {
					$ListPelangganAll->where('pelanggan.cancel', 1);
				} else if ($req->status == 'active') {
					$ListPelangganAll->where('aktivasi.status', 'active');
				} else if ($req->status == 'new') {
					$ListPelangganAll->whereNull('aktivasi.status');
				} else if ($req->status == 'suspend') {
					$ListPelangganAll->whereNotNull('pelanggan.suspend_id');
				} else if ($req->status == 'aktif') {
					$ListPelangganAll->where('pelanggan.cancel', 0);
				}
				$ListPelangganAll->where('pelanggan.status', 'aktivasi');
				break;
			case 'cancel':
				$ListPelangganAll->where('pelanggan.cancel', 1);
				break;
			default:
				break;
		}

		// pelanggan company

		if ($company_id != null && $company_id != "") {
			$company = Company::find($company_id);
			$ListPelangganAll->where($company->type . '_id', '=', $company_id);
		} else {
			$company = Auth::user()->company();
			if ($company->type != null) {
				$ListPelangganAll->where($company->type . '_id', '=', $company->company_id);
			}
		}



		// wilayah
		if ($req->wilayah_id != "" && $req->wilayah_id != null) {
			$ListPelangganAll->where('pelanggan.wilayah_id', $req->wilayah_id);
		}

		// SEARCH
		if ($req->get('search') != null) {
			$search = $req->search;
			$ListPelangganAll->where(function ($query) use ($search) {
				$query->where('nama_pelanggan', 'LIKE', "%{$search}%")
					->orWhere('nama_paket', 'LIKE', "%{$search}%")
					->orWhere('telepon', 'LIKE', "%{$search}%")
					->orWhere('email', 'LIKE', "%{$search}%")
					->orWhere('presales.address', 'LIKE', "%{$search}%")
					->orWhere('isp.name', 'LIKE', "%{$search}%")
					->orWhere('osp.name', 'LIKE', "%{$search}%");
			});
		}

		// deleted at
		$ListPelangganAll->whereNull('pelanggan.deleted_at');

		//list sla bulan ini
		$ListPelangganAll
			->select($select);


		// PAGINATE
		$ListSend = $ListPelangganAll->paginate($req->get('per_page'));

		// dd($ListSend);
		return $ListSend;
	}

	public function withPacket()
	{
		$paket = PaketBerlangganan::find($this->paket_id);
		$this->paketBerlangganan = $paket;

		return $this;
	}

	//saat pelanggan suspend di approve oleh osp, fungsi ini akan berjalan
	public function createRefundItemWhenApprove($suspend_id)
	{
		$suspend = DB::table('suspend')->where('suspend_id', $suspend_id)->where('tgl_suspend', 'LIKE', date('Y-m') . "%")->first();
		if ($suspend) {
			$this->InsertDataRefundItem($suspend);
		} else {
			$suspend = DB::table('suspend')->where('suspend_id', $suspend_id)->first();
			$bulan = (int) date('m', strtotime($suspend->tgl_suspend));
			$tahun = (int) date('Y', strtotime($suspend->tgl_suspend));
			$bulan_sekarang = (int) date('m');
			$tahun_sekarang = (int) date('Y');

			if ($bulan > $bulan_sekarang && $tahun >= $tahun_sekarang) {
				//jika approve == tgl suspend yang diajukan akan masuk ke script ini
				DB::table('pelanggan')->where('pelanggan_id', $suspend->pelanggan_id)->update([
					'hold_pengembalian' => 0
				]);
			} else if (($bulan + 1) == $bulan_sekarang && $tahun == $tahun_sekarang) {
				//hitung komisi pengembalian
				$setting = Configuration::find(1);
				$invoice_item_pelanggan = Invoice::findInvoiceItemByPelangganId($suspend->pelanggan_id);
				$invoice = DB::table('invoice')->where('invoice_id', $invoice_item_pelanggan->invoice_id)
					->join('companies as c', 'c.company_id', '=', 'invoice.isp_id')
					->first();
				$isp = DB::table('companies')->where('company_id', $invoice->isp_id)->first()->name;
				$wilayah = DB::table('pelanggan')->join('wilayah as w', 'w.wilayah_id', '=', 'pelanggan.wilayah_id')
					->where('pelanggan_id', $suspend->pelanggan_id)
					->first();

				$data_refund = $this->InsertDataRefundItem($suspend);
				$total_tagihan = $invoice_item_pelanggan->amount;
				$total_refund = $data_refund['amount'];
				$komisi_openaccess = $setting->admin_fee;
				$komisi_osp = ceil($total_tagihan - $total_refund - $komisi_openaccess);

				$created_at = date_create(date('Y-m-d', strtotime($invoice->created_at)));

				//create mutasi
				Saldo::potong_saldo_dibekukan(1, $total_tagihan);
				Saldo::potong_saldo(1, ($komisi_osp + $total_refund), trans('invoice::invoices.tagihan bulanan pelanggan dari isp', [
					'bulan' => strftime('%B %Y', $created_at->getTimestamp()),
					'isp' => $isp,
					'wilayah' => $wilayah->name
				]));
				Saldo::tambah_saldo($invoice->osp_id, $komisi_osp, trans('invoice::invoices.tagihan bulanan pelanggan dari isp', [
					'bulan' => strftime('%B %Y', $created_at->getTimestamp()),
					'isp' => $isp,
					'wilayah' => $wilayah->name
				]));
				Saldo::tambah_saldo($invoice->isp_id, $total_refund, $data_refund['deskripsi']);
			}
		}
	}

	public function InsertDataRefundItem($suspend)
	{
		$pelanggan = Self::find($suspend->pelanggan_id);
		$invoice_item_pelanggan = Invoice::findInvoiceItemByPelangganId($suspend->pelanggan_id);

		$total_hari = (int) date('t', strtotime($suspend->tgl_suspend));
		$sisa_hari = $total_hari - (int) date('d', strtotime($suspend->tgl_suspend)) + 1;
		$amount = ($sisa_hari / $total_hari) * $pelanggan->biaya_mrc;

		$refund_id = DB::table('refund_item')->insertGetId([
			'invoice_item_id' => $invoice_item_pelanggan->invoice_item_id,
			'refund_amount' => $amount,
			'refund_type' => 'suspend',
			'description' => trans('invoice::invoices.refund item suspend', [
				'jumlah' => $this->rupiah(ceil($amount)),
				'name' => $pelanggan->nama_pelanggan
			]),
			'created_at' => date('Y-m-d H:i:s'),
			'status' => 'pending'
		]);

		$deskripsi_refund = DB::table('refund_item')->where('refund_id', $refund_id)->first()->description;

		return [
			'deskripsi' => $deskripsi_refund,
			'amount' => $amount
		];
	}

	public function complete_activation()
	{
		// Dapatkan data dari saldo biaya pelanggan
		DB::beginTransaction();
		$saldo_biaya_pelanggan = SaldoBiayaPelanggan::where('pelanggan_id', $this->pelanggan_id)->first();
		$setting = Configuration::find(1);

		// Jika saldo biaya pelanggan tidak ditemukan, Abort Proses !!!
		if (!$saldo_biaya_pelanggan) return abort(500, trans('pelanggan::pelanggans.messages.saldo biaya pelanggan not found'));

		$mrc = $saldo_biaya_pelanggan->mrc;
		$biaya_otc = $saldo_biaya_pelanggan->otc;


		// Hitung fee openaccess dan komisi OSP untuk biaya instalasi
		$fee_openaccess = $biaya_otc * ($setting->persentase_refund_openaccess / 100);
		$komisi_osp = $biaya_otc - $fee_openaccess;
		$pengembalian_ke_isp = 0;
		if ($saldo_biaya_pelanggan->saldo_mengendap > $saldo_biaya_pelanggan->total_biaya) {
			$pengembalian_ke_isp = $saldo_biaya_pelanggan->saldo_mengendap - $saldo_biaya_pelanggan->total_biaya;

			Saldo::tambah_saldo($this->isp_id, $pengembalian_ke_isp, trans('pelanggan::pelanggans.messages.pengembalian kelebihan tagihan', [
				'nama_pelanggan' => $this->nama_pelanggan
			]));
		}


		// Kurangi saldo openaccess untuk pemberian komisi ke osp untuk biaya instalasi
		Saldo::potong_saldo_dibekukan(1, $biaya_otc + $pengembalian_ke_isp); // dipotong dari biaya otc
		Saldo::potong_saldo(1, $komisi_osp, trans('pelanggan::pelanggans.messages.biaya instalasi', [
			'nama_pelanggan' => $this->nama_pelanggan
		]));


		// Tambah saldo OSP 
		Saldo::tambah_saldo($this->osp_id, $komisi_osp, trans('pelanggan::pelanggans.messages.biaya instalasi', [
			'nama_pelanggan' => $this->nama_pelanggan
		]));

		$date_pelanggan_aktif = date('Y-m-d');

		// Untuk dapatkan data osp_id
		$data_request_wilayah = Pengajuan::where('isp_id', $this->isp_id)
			->where('wilayah_id', $this->wilayah_id)
			->first();

		if (!($data_request_wilayah)) return abort(404, trans('wilayah::pengajuans.messages.404'));

		$osp = Company::find($this->osp_id);

		$invoice = Invoice::where('isp_id', $this->isp_id)
			->where('periode', 'like', date('Y-m') . "%")
			->where('status', '!=', 'settlement')
			->first();

		$isp_id = $this->isp_id;
		$mrc = $saldo_biaya_pelanggan->mrc;
		$ppn = $osp->ppn === 1 ? 10 : 0;
		$dpp = $osp->ppn === 1 ? round($mrc / 1.1) : $mrc;
		$total_ppn = $mrc - $dpp;

		$potongan_prorata = (new Invoice())->generatePotonganProrata($date_pelanggan_aktif, $dpp);

		// Jika pelanggan aktif bukan tanggal 1, Hitung Prorata!
		if (!($invoice)) {
			$invoice = Invoice::create_invoice_activation_pelanggan($this, $data_request_wilayah, $ppn);
			// update amount, ppn, dpp and title on invoice
			$wilayah = Pengajuan::detailWilayahByPengajuan($data_request_wilayah->request_wilayah_id);
			$nama_wilayah   = $wilayah->name;
			$title = "Invoice MRC pelanggan wilayah $nama_wilayah";
			Invoice::where('invoice_id', $invoice->invoice_id)
				->update([
					'amount' => $mrc - $potongan_prorata,
					'nominal_ppn' => $total_ppn,
					'nominal_dpp' => $dpp,
					'title' => $title
				]);
		}

		InvoiceItem::create([
			'invoice_id' => $invoice->invoice_id,
			'pelanggan_id' => $this->pelanggan_id,
			'amount' => $mrc - $potongan_prorata,
			'dpp' => $dpp - $potongan_prorata,
			'total_ppn' => $total_ppn,
			'ppn' => $ppn
		]);

		if ($potongan_prorata > 0) {

			$komisi_osp = $mrc - $potongan_prorata - $setting->admin_fee;

			Saldo::potong_saldo_dibekukan(1, $potongan_prorata);
			Saldo::potong_saldo(1, $potongan_prorata, trans('pelanggan::pelanggans.messages.prorata', [
				'nama_pelanggan' => $this->nama_pelanggan
			]));

			// Pengembalian dana prorata ke isp
			Saldo::tambah_saldo($isp_id, $potongan_prorata, trans('pelanggan::pelanggans.messages.prorata', [
				'nama_pelanggan' => $this->nama_pelanggan
			]));
		}

		DB::commit();

		return true;
	}

	public function complete_activation_estimation()
	{
		// Dapatkan data dari saldo biaya pelanggan
		$komisi = [
			'isp' => 0,
			'osp' => 0,
			'openaccess' => 0
		];
		$saldo_biaya_pelanggan = SaldoBiayaPelanggan::where('pelanggan_id', $this->pelanggan_id)->first();
		$setting = Configuration::find(1);


		$mrc = $saldo_biaya_pelanggan->mrc;
		$biaya_otc = $saldo_biaya_pelanggan->otc;


		// Hitung fee openaccess dan komisi OSP untuk biaya instalasi
		$fee_openaccess = $biaya_otc * ($setting->persentase_refund_openaccess / 100);
		$komisi_osp = $biaya_otc - $fee_openaccess;
		$komisi['osp'] += $komisi_osp - $setting->admin_fee;
		$komisi['openaccess'] += $fee_openaccess + $setting->admin_fee;

		$kelebihan_pembayaran = 0;

		if ($saldo_biaya_pelanggan->saldo_mengendap > $saldo_biaya_pelanggan->total_biaya) {
			$kelebihan_pembayaran = $saldo_biaya_pelanggan->saldo_mengendap - $saldo_biaya_pelanggan->total_biaya;
		}

		$date_pelanggan_aktif = date('Y-m-d');


		// Jika pelanggan aktif bukan tanggal 1, Hitung Prorata!

		if (date('Y-m-01') != $date_pelanggan_aktif) {
			$refund_amount = (new Invoice())->generatePotonganProrata($date_pelanggan_aktif, $mrc);
			$komisi['pengembalian_prorata'] = $refund_amount;
		}

		$komisi['kelebihan_pembayaran'] = $kelebihan_pembayaran;
		$komisi['percentage_openaccess'] = $setting->persentase_refund_openaccess;
		$komisi['percentage_osp'] = 100 - $setting->persentase_refund_openaccess;

		return $komisi;
	}

	public function cancel_proses()
	{
		$saldo_biaya_pelanggan = SaldoBiayaPelanggan::where('pelanggan_id', $this->pelanggan_id)->where('settlement', 0)->first();
		$setting = Configuration::find(1);
		$otc = $saldo_biaya_pelanggan->otc;
		$mrc = $saldo_biaya_pelanggan->mrc;

		if ($this->status == 'so') {
			$total_tagihan = $saldo_biaya_pelanggan->total_biaya;

			Saldo::potong_saldo_dibekukan(1, $total_tagihan);
			Saldo::potong_saldo(1, $total_tagihan, trans('pelanggan::pelanggans.messages.fee cancel survey isp', ['nama_pelanggan' => $this->nama_pelanggan]));

			Saldo::tambah_saldo($this->isp_id, $total_tagihan, trans('pelanggan::pelanggans.messages.fee cancel survey isp', ['nama_pelanggan' => $this->nama_pelanggan]));

			$saldo_biaya_pelanggan->settlement = 1;
			$saldo_biaya_pelanggan->save();

			return true;
		}

		if ($this->status == 'survey') {
			$persentase_biaya_osp = 10;
			$persentase_biaya_admin = $setting->persentase_refund_openaccess;

			$komisi_osp = ($persentase_biaya_osp / 100) * $otc;
			$komisi_openaccess = ($persentase_biaya_admin / 100) * $otc;
			$pengembalian_ke_isp = $otc - ($komisi_osp + $komisi_openaccess) + $mrc;
		}

		if ($this->status == 'instalasi') {
			$persentase_biaya_osp = 100 - $setting->persentase_refund_openaccess;
			$persentase_biaya_admin = $setting->persentase_refund_openaccess;

			$komisi_openaccess = ($setting->persentase_refund_openaccess / 100) * $otc;
			$komisi_osp = $otc - $komisi_openaccess;
			$pengembalian_ke_isp = $mrc;
		}

		if ($saldo_biaya_pelanggan->saldo_mengendap > $saldo_biaya_pelanggan->total_biaya) {
			$pengembalian_ke_isp += $saldo_biaya_pelanggan->saldo_mengendap - $saldo_biaya_pelanggan->total_biaya;
		}



		// Potong saldo di openaccess 
		Saldo::potong_saldo_dibekukan(1, $saldo_biaya_pelanggan->saldo_mengendap);
		Saldo::potong_saldo(1, $komisi_osp + $pengembalian_ke_isp, trans('pelanggan::pelanggans.messages.refund cancel survey openaccess', ['nama_pelanggan' => $this->nama_pelanggan]));

		// Komisi untuk osp
		Saldo::tambah_saldo($this->osp_id, $komisi_osp, trans('pelanggan::pelanggans.messages.refund cancel survey openaccess', ['nama_pelanggan' => $this->nama_pelanggan]));

		// Pengembalian ke isp
		Saldo::tambah_saldo($this->isp_id, $pengembalian_ke_isp, trans('pelanggan::pelanggans.messages.refund cancel survey openaccess', ['nama_pelanggan' => $this->nama_pelanggan]));

		$saldo_biaya_pelanggan->biaya_osp = $komisi_osp;
		$saldo_biaya_pelanggan->persentase_biaya_osp = $persentase_biaya_osp;


		$saldo_biaya_pelanggan->pengembalian_isp = $pengembalian_ke_isp;


		$saldo_biaya_pelanggan->persentase_biaya_admin = $persentase_biaya_admin;
		$saldo_biaya_pelanggan->biaya_admin = $komisi_openaccess;
		$saldo_biaya_pelanggan->settlement = 1;
		$saldo_biaya_pelanggan->save();
		// dd($saldo_biaya_pelanggan);

		return true;
	}




	public static function saveTeknisiInstalasi($teknisi_instalasi, $instalasi_id)
	{
		$jumlah_data = count($teknisi_instalasi[0]);
		$instalasi_staff = DB::table('instalation_staff');
		$data = $instalasi_staff->where('instalasi_id', $instalasi_id);
		if ($data->get()) {
			$data->delete();
		}
		for ($i = 0; $i < $jumlah_data; $i++) {
			DB::table('instalation_staff')->insert([
				'instalasi_id' => $instalasi_id,
				'user_id' => $teknisi_instalasi[0][$i]
			]);
		}
	}
	public static function getOptionTeknisi($osp_id)
	{

		$data = DB::table('teknisi')->select('users.full_name as nama_teknisi', 'teknisi.user_id')
			->join('users', 'teknisi.user_id', '=', 'users.id')
			->whereNull('users.deleted_at')
			->where('company_id', $osp_id)
			->get()
			->map(function ($item) {
				$item->status = false;
				return $item;
			});

		return $data;
	}

	public static function getEstimasiInstalasi($type_ep)
	{
		$config = Configuration::find(1);
		$estimasi_endpoint = 0;
		switch ($type_ep) {
			case 'ODP':
				$estimasi_endpoint = $config->sla_odp;
				break;
			case 'JB':
				$estimasi_endpoint = $config->sla_join_box;
				break;

			default:
				$estimasi_endpoint = $config->sla_fixing_stack;
				break;
		}


		$detik = $estimasi_endpoint * 86400;
		return $detik;
	}


	public function rating()
	{
		return $this->generateRating($this->pelanggan_id);
	}
	private function generateRating($pelanggan_id)
	{
		$timers = DB::table('activity_timer')
			->select([
				DB::raw('SUM(activity_timer.total_waktu) as total_waktu'),
				'activity_tipe.*',
				'activity_timer.pelanggan_id',
				'companies.type',
				'activity_timer.company_id'
			])
			->where('activity_timer.pelanggan_id', $pelanggan_id)
			->where('activity_timer.company_id', $this->isp_id)
			->groupBy('activity_timer.activity_tipe_id')
			->join('activity_tipe', 'activity_tipe.activity_tipe_id', 'activity_timer.activity_tipe_id')
			->join('companies', 'companies.company_id', 'activity_timer.company_id')
			->get();

		$rating_osp = [];
		$rating_isp = [];
		foreach ($timers as $key => $timer) {
			if ($timer->activity_tipe_id != 9) {
				$data = [];
				$dayOnSecond = 86400;

				// Kurang 1 digunakan untuk mendapatkan hari yang lewat dari estimasi
				$totalDay = ceil((int)$timer->total_waktu / $dayOnSecond) - 1;
				$totalDay = $totalDay < 0 ? 0 : $totalDay;
				$point_rating = $this->total_rating - $totalDay;


				$trans = $this->getDescriptionRating($timer->activity_tipe_id, $this->nama_pelanggan);
				$data = [
					'rating' => $point_rating,
					'description' => $trans
				];

				if ($timer->type == 'osp') {
					$rating_osp[] = $data;
				} else {
					$rating_isp[] = $data;
				}
			}
		}

		$created_at = $this->getTanggalAktivasi($pelanggan_id);
		$start_time = Company::getJamKerjaCompanySaatProses($this->isp_id, $created_at);
		if (strtotime($created_at) > strtotime($start_time)) {
			$start_time = $created_at;
		}
		// end time
		$end_time = date('Y-m-d H:i:s');
		// total waktu
		$total_waktu = strtotime($end_time) - strtotime($start_time);
		$totalDay = ceil($total_waktu / $this->estimasi_instalasi);
		$totalDay = $totalDay < 0 ? 0 : $totalDay;
		$point_rating = $this->total_rating - $totalDay;
		$trans = $this->getDescriptionRating(9, $this->nama_pelanggan);

		$rating_isp[] = [
			'rating' => $point_rating,
			'description' => $trans
		];


		return [
			'rating' => [
				'isp' => $rating_isp,
				// 'osp' => $rating_osp
			],
			'pengembalian' => $this->complete_activation_estimation()
		];
	}

	private function getDescriptionRating($activity_tipe_id, $nama_pelanggan)
	{
		$trans = '';
		switch ($activity_tipe_id) {
			case 1:
				$trans = trans('analytic::analytics.rating.pemberian jadwal survey', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			case 2:
				$trans = trans('analytic::analytics.rating.penentuan jadwal survey', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			case 3:
				$trans = trans('analytic::analytics.rating.survey pelanggan', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			case 4:
				$trans = trans('analytic::analytics.rating.accept perubahan pelanggan', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			case 5:
				$trans = trans('analytic::analytics.rating.pemberian jadwal instalasi', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			case 6:
				$trans = trans('analytic::analytics.rating.penentuan jadwal instalasi', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			case 7:
				$trans = trans('analytic::analytics.rating.instalasi pelanggan', [
					'nama_pelanggan' => $nama_pelanggan
				]);



				break;

			case 8:
				$trans = trans('analytic::analytics.rating.aktivasi pelanggan', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			case 9:
				$trans = trans('analytic::analytics.rating.approve aktivasi pelanggan', [
					'nama_pelanggan' => $nama_pelanggan
				]);

				break;

			default:
				# code...
				break;
		}


		return $trans;
	}

	public function getTanggalAktivasi($pelanggan_id)
	{
		$aktivasi = DB::table('activity_timer')->where('pelanggan_id', $pelanggan_id)
			->where('activity_tipe_id', 8)
			->orderBy('activity_id', 'desc')
			->first();

		if ($aktivasi == null) {
			$cek = DB::table('activity_timer')->where('pelanggan_id', $pelanggan_id)
				->where('activity_tipe_id', 9)
				->orderBy('activity_id', 'desc')
				->first();

			$send = $cek->end_time;
		} else {
			$send = $aktivasi->end_time;
		}

		return $send;
	}
}
