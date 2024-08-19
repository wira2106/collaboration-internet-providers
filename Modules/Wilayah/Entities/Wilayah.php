<?php

namespace Modules\Wilayah\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Company\Entities\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\PaketForIsp;
use Modules\Configuration\Entities\Configuration;
use Modules\Presale\Entities\Endpoint;
use Modules\Presale\Entities\Presale;
use Modules\Presale\Events\EndpointIsCreated;
use Modules\Saldo\Entities\Saldo;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Emails\RequestWilayahMail;
use Modules\Wilayah\Entities\regions\Provinces;
use Modules\Wilayah\Entities\regions\Regencies;
use Modules\Wilayah\Entities\regions\Districts;
use Modules\Wilayah\Entities\regions\Villages;
use Modules\Wilayah\Transformers\SaveEndpointFromWilayahTransformer;

class Wilayah extends Model
{
	use SoftDeletes;
	protected $table = 'wilayah';
	public $translatedAttributes = [];
	protected $primaryKey = "wilayah_id";
	protected $fillable = [
		'company_id',
		'name',
		'provinces_id',
		'regencies_id',
		'districts_id',
		'villages_id',
		'address',
		'post_code',
		'open',
		'status_presale',
	];


	public function saveFromWilayah($req)
	{
		$wilayah_id 	= $req->wilayah_id;
		$nama_wilayah = $req->nama_wilayah;
		$provinces_id = $req->provinces_id;
		$regencies_id = $req->regencies_id;
		$villages_id 	= $req->villages_id;
		$districts_id = $req->districts_id;
		$postal_code 	= $req->postal_code;
		$company_id		= $req->company_id;
		$company 			= Company::getCompanyUser();

		$dataSave = [
			"name" => $nama_wilayah,
			"provinces_id" => $provinces_id,
			"regencies_id" => $regencies_id,
			"districts_id" => $districts_id,
			"villages_id" => $villages_id,
			"post_code" => $postal_code,
			"company_id" => $company_id
		];

		if ($wilayah_id != null) {
			// update Data
			$dataSave["updated_by"] = Auth::User()->id;
			$dataSave["updated_at"]  = date("Y-m-d H:i:s");
			// UPDATE INTO DATABASE
			DB::table("wilayah")->where("wilayah_id", $wilayah_id)->update($dataSave);
			$dataSave["created"] = false;
		} else {
			// create Data
			$dataSave["created_at"]  = date("Y-m-d H:i:s");
			$dataSave["created_by"] = Auth::User()->id;
			// add into databaseS
			$wilayah_id = DB::table("wilayah")->insertGetId($dataSave);
			$dataSave["created"] = true;
		}
		$dataSave["wilayah_id"] = $wilayah_id;

		return $dataSave;
	}

	public function getTableName()
	{
		return $this->table;
	}

	public function hasAccess(User $user)
	{
		$user_company_id = $user->userCompanies->company_id;
		if ($user->hasAllAccessCompanies()) return true;

		if ($user_company_id === $this->company_id) return true;

		$cek_request_wilayah = Pengajuan::where('wilayah_id', $this->wilayah_id)
			->where('isp_id', $user_company_id)
			->first();

		if ($cek_request_wilayah) return true;

		abort(Response::HTTP_FORBIDDEN, "Forbidden");
	}

	public function listWilayahMap()
	{
		$select = [
			"wilayah_id",
			"wilayah.name",
			"companies.name as company_name",
			"provinces.name as provinsi",
			"regencies.name as kabupaten",
			// "districts.name as kecamatan",
			// "villages.name as kelurahan",
		];
		$wilayah = Wilayah::select($select)
			->join("companies", "companies.company_id", "=", "wilayah.company_id")
			->join("provinces", "provinces.id", "=", "wilayah.provinces_id")
			->join("regencies", "regencies.id", "=", "wilayah.regencies_id")
			// ->join("districts","districts.id","=","wilayah.districts_id")
			// ->join("villages","villages.id","=","wilayah.villages_id")
			->orderBy("wilayah.name")
			->where('open', 1)
			->get();

		return $wilayah;
	}

	public function detailWilayah()
	{
		$this->provinsi 	= Provinces::find($this->provinces_id)->name ??  '';
		$this->kabupaten 	= Regencies::find($this->regencies_id)->name ?? '';
		$this->kecamatan 	= Districts::find($this->districts_id)->name ?? '';
		$this->kelurahan 	= Villages::find($this->villages_id)->name ?? '';
		// $this->site 		= Presale::where('wilayah_id', $this->wilayah_id)->where('status_id', 1)->count();
		$this->site 		= DB::table('presales')
			->join('active_presales', 'presales.presale_id', 'active_presales.presale_id')
			->where('wilayah_id', $this->wilayah_id)->where('status_id', 1)->count();
		$this->end_point 	= Endpoint::where('wilayah_id', $this->wilayah_id)->count();

		return $this;
	}

	public function getParticipant()
	{
		$this->participant = DB::table("request_wilayah")
			->select(["companies.company_id", "companies.name", "companies.logo_perusahaan"])
			->join("companies", "companies.company_id", "=", "request_wilayah.isp_id")
			->where("companies.deleted_at", NULL)
			->where("request_wilayah.status", "!=", "rejected")
			->where("wilayah_id", $this->wilayah_id)
			->get();
		return $this;
	}

	public function endpoint()
	{
		$endpoint = Endpoint::select([
			'end_point_id',
			'lat_end_point',
			'lon_end_point',
			'tipe',
			'nama_end_point',
			'settlement_at'
		])->where('wilayah_id', $this->wilayah_id)->get();

		return $endpoint;
	}

	public function presales()
	{
		$presales = Presale::select(['lat', 'lon', 'presale_id'])
			->where('wilayah_id', $this->wilayah_id)
			->where('status_id', 1)
			->get();

		return $presales;
	}

	public function total()
	{
		return self::all()->count();
	}

	public function serverPaginationFilteringForISP(Request $request)
	{
		$wilayah  = DB::table('request_wilayah')
			->select(['wilayah.*', 'companies.name as nama_company'])
			->join('wilayah', 'wilayah.wilayah_id', 'request_wilayah.wilayah_id')
			->join('companies', 'companies.company_id', '=', 'wilayah.company_id')
			->where('request_wilayah.isp_id', Auth::user()->userCompanies->company_id);

		if ($request->get('search') !== null) {
			$term = $request->get('search');
			$wilayah->where(function ($query) use ($term) {
				$query->orWhere('companies.name', 'LIKE', "%{$term}%")
					->orWhere('wilayah.name', 'LIKE', "%{$term}%");
			});
		}

		if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
			$order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

			$wilayah->orderBy('wilayah.' . $request->get('order_by'), $order);
		} else {
			$wilayah->orderBy('request_wilayah.wilayah_id', 'asc');
		}

		return $wilayah->paginate($request->get('per_page', 10));
	}

	public function add_new_endpoint($endpoints, $bayar = true)
	{
		foreach ($endpoints as $key => $endpoint) {
			$data = [
				'wilayah_id' => $this->wilayah_id,
				'nama_end_point' => $endpoint['nama_end_point'],
				'tipe' => $endpoint['tipe'],
				'lat_end_point' => $endpoint['position']['lat'],
				'lon_end_point' => $endpoint['position']['lng'],
				'address' => $endpoint['address']
			];

			$endpoint = (new Endpoint)->fill($data);
			$endpoint->save();

			new EndpointIsCreated($endpoint, $data, $bayar);
		}
	}

	public function request_presale()
	{
		$emails = (new Company)->admin_email(1);

		$data = [
			'openaccess' => Company::find(1),
			'osp' => Company::find($this->company_id),
			'url' => route('api.presale.presales.index', [
				'wilayah' => $this->wilayah_id
			]),
			'wilayah' => $this,
			'title' => trans('wilayah::wilayahs.mail.request presale successfull.title')
		];

		Mail::to($emails)->send(new RequestWilayahMail($data));
	}

	public function paket_berlangganan()
	{
		return DB::table('request_wilayah')
			->select([
				'paket_berlangganan.nama_paket',
				'paket_berlangganan.biaya_otc',
				'paket_berlangganan.harga_paket',
				'paket_berlangganan.paket_id',
				DB::raw('(sum(paket_berlangganan.harga_paket + paket_berlangganan.biaya_otc)) as biaya')
			])
			->join('paket_for_isp', 'paket_for_isp.request_wilayah_id', 'request_wilayah.request_wilayah_id')
			->join('paket_berlangganan', 'paket_for_isp.paket_id', 'paket_berlangganan.paket_id')
			->where('request_wilayah.isp_id', Auth::user()->company()->company_id)
			->where('request_wilayah.wilayah_id', $this->wilayah_id)
			->get();
	}
}
