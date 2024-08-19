<?php

namespace Modules\Presale\Entities;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Configuration\Entities\Configuration;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Invoice\Entities\Invoice;
use Modules\Invoice\Entities\InvoiceItemKonfirmasiPresale;
use Modules\Presale\Emails\SuccessConfirmationPresale;
use Modules\Presale\Events\PresaleIsConfirmed;
use Modules\Presale\Infowindows\InfoWindowDetailPresale;
use Modules\Presale\Traits\ConvertIconPresale;
use Modules\Saldo\Entities\MutasiSaldo;
use Modules\Saldo\Entities\Saldo;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Entities\Wilayah;

class Presale extends Model
{
    use SoftDeletes, InfoWindowDetailPresale, ConvertIconPresale, ConvertToCurrency;

    protected $table = 'presales';
    protected $fillable = [
        "lat",
        "lon",
        "end_point_id",
        "panjang_kabel",
        "biaya_kabel",
        "biaya_instalasi",
        "total_biaya",
        "provinces_id",
        "regencies_id",
        "districts_id",
        "villages_id",
        "nama_gang",
        "no_rumah",
        "kode_pos",
        "address",
        "keterangan",
        "class_id",
        "status_id",
        "foto_rumah",
        "site_id",
        "wilayah_id"
    ];

    protected $primaryKey = "presale_id";

    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {


        $presale = DB::table('presales')->select([
            'presales.presale_id',
            'presales.lat',
            'presales.lon',
            'presales.class_id',
            'presales.status_id',
            'presales.wilayah_id',
            'presales.end_point_id',
            'presales.created_at',
            'presales.nama_gang',
            'presales.no_rumah',
            'presales.panjang_kabel',
            'presales.site_id',
            'presales.address',
            'active_presales.active_presale_id',
            'presale_class.class_name',
            'presale_class.icon',
        ])
            ->join('presale_class', 'presale_class.class_id', 'presales.class_id')
            ->leftjoin('active_presales', 'active_presales.presale_id', 'presales.presale_id')
            ->where('deleted_at', null);

        if ($request->wilayah_id) {
            $presale = $presale->where('presales.wilayah_id', $request->wilayah_id);
        }

        $searchQuery = $request->search;
        if ($searchQuery) {
            $presale->where(function ($query) use ($searchQuery) {
                $query->orWhere('presales.site_id', 'like', "%$searchQuery%")
                    ->orWhere('presales.address', 'like', "%$searchQuery%")
                    ->orWhere('presales.nama_gang', 'like', "%$searchQuery%")
                    ->orWhere('presales.no_rumah', 'like', "%$searchQuery%");
            });
        }

        if ($request->class_id) $presale->where('presales.class_id', $request->class_id);
        if ($request->end_point_id) $presale->where('presales.end_point_id', $request->end_point_id);
        if ($request->status_id) $presale->where('presales.status_id', $request->status_id);


        // dd($presale->get(),$request->wilayah_id);
        return $presale->paginate($request->get('per_page', 300));
    }

    public function serverPaginationFilteringForIsp(Request $request): LengthAwarePaginator
    {
        $presale = DB::table('request_wilayah')->select([
            'presales.presale_id',
            'presales.lat',
            'presales.lon',
            'presales.class_id',
            'presales.status_id',
            'presales.wilayah_id',
            'presales.end_point_id',
            'presales.created_at',
            'presales.nama_gang',
            'presales.no_rumah',
            'presales.panjang_kabel',
            'presales.site_id',
            'presales.address',
            'active_presales.active_presale_id',
            'presale_class.class_name',
            'presale_class.icon',
        ])
            ->where('isp_id', Auth::user()->userCompanies->company_id)
            ->where('request_wilayah.status', 'accepted')
            ->where('presales.deleted_at', null)
            ->join('active_presales', 'active_presales.osp_id', 'request_wilayah.osp_id')
            ->join('presales', 'presales.presale_id', 'active_presales.presale_id')
            ->join('presale_class', 'presale_class.class_id', 'presales.class_id');

        if ($request->wilayah_id) $presale->where('request_wilayah.wilayah_id', $request->wilayah_id);

        $searchQuery = $request->search;
        if ($searchQuery) {
            $presale->where(function ($query) use ($searchQuery) {
                $query->orWhere('presales.site_id', 'like', "%$searchQuery%")
                    ->orWhere('presales.address', 'like', "%$searchQuery%")
                    ->orWhere('presales.nama_gang', 'like', "%$searchQuery%")
                    ->orWhere('presales.no_rumah', 'like', "%$searchQuery%");
            });
        }

        if ($request->class_id) $presale->where('presales.class_id', $request->class_id);
        if ($request->end_point_id) $presale->where('presales.end_point_id', $request->end_point_id);
        if ($request->status_id) $presale->where('presales.status_id', $request->status_id);

        // dd($presale->get(),$request->wilayah_id);
        return $presale->paginate($request->get('per_page', 300));
    }

    public function withIcon()
    {
        $this->icon = PresaleClass::find($this->class_id)->icon;
    }

    public function findManyJoinActivePresale($ids)
    {
        $query = Presale::select([
            'presales.*',
            'active_presales.*',
            'presale_class.icon',
        ])
            ->whereIn('presales.presale_id', $ids)
            ->join('active_presales', 'active_presales.presale_id', 'presales.presale_id')
            ->join('presale_class', 'presale_class.class_id', 'presales.class_id')
            ->get();
        return $query;
    }

    public function updatePathLine($pathline)
    {
        $presale_id = $this->presale_id;

        DB::beginTransaction();
        try {
            DB::table('jalur_kabel')
                ->where('presale_id', $presale_id)
                ->delete();

            $arrayPathLine = [];
            foreach ($pathline as $key => $path) {
                $arrayPathLine[] = [
                    "lat" => $path['lat'],
                    "lon" => $path['lng'],
                    "presale_id" => $presale_id
                ];
            }
            $insert = DB::table('jalur_kabel')
                ->insert($arrayPathLine);


            DB::commit();
        } catch (\Exception $err) {
            return abort(500);
        }
    }

    public function company_id()
    {
        $wilayah = Wilayah::where('wilayah_id', $this->wilayah_id)->first();
        return $wilayah->company_id;
    }

    public function wilayah()
    {
        return  Wilayah::select('wilayah.*', 'a.name as nama_osp')
            ->join('companies as a', 'wilayah.company_id', 'a.company_id')
            ->where('wilayah.wilayah_id', $this->wilayah_id)
            ->first();
    }

    public function withDataEndpoint()
    {
        $data = $this;
        $data->icon = $this->getIconUrl($this->class_id, $this->status_id);
        $endpoint = Endpoint::find($this->end_point_id);
        $updated = false;

        if ($endpoint) {
            $data->lat_ep = $endpoint->lat_end_point;
            $data->lon_ep = $endpoint->lon_end_point;
            $data->endpoint_name = $endpoint->nama_end_point;
            $data->type_ep = $endpoint->tipe;
            if ($endpoint->tipe == 'ODP') {
                $data->icon_ep = url('/modules/presale/img/odp-marker.ico');
            } else if ($endpoint->tipe == 'JB') {
                $data->icon_ep = url('/modules/presale/img/jb-marker.ico');
            } else {
                $data->icon_ep = url('/modules/presale/img/fixing-slack-marker.ico');
            }
        }

        if ($this->updated_at) {
            $user = User::find($this->updated_by);
        } else {
            $user = User::find($this->created_by);
        }

        if ($user) {
            $edited_text = "Diedit pada $this->updated_at WITA oleh $user->full_name";
        } else {
            $edited_text = "Diedit pada $this->updated_at WITA";
        }

        $data->path = DB::table('jalur_kabel')->select('lat', 'lon as lng')->where('presale_id', $this->presale_id)->get();
        $data->edited_text = $edited_text;

        // dd($endpoint);
        return $data;
    }

    public function routes()
    {
        return DB::table('jalur_kabel')->select('lat', 'lon')->where('presale_id', $this->presale_id)->get();
    }

    public function findById($id)
    {
        $select = [
            'a.*',
            'b.nama_end_point',
            'b.lat_end_point',
            'b.lon_end_point',
        ];

        $data = DB::table("$this->table as a")
            ->select($select)
            ->join('end_point as b', 'b.end_point_id', 'a.end_point_id')
            ->where('presale_id', $id)
            ->first();

        $data->route_line = DB::table('jalur_kabel')->where('presale_id', $id)->get();

        return $data;
    }

    public function hasAccessPresale($osp_id = null)
    {
        if (!$osp_id) {
            if (!Auth::user()->hasAllAccessCompanies()) {
                $osp_id = Auth::user()->userCompanies->company_id;
            } else {
                return true;
            }
        }
        return DB::table('active_presales')->where('presale_id', $this->presale_id)->where('osp_id', $osp_id)->count() > 0;
    }

    public function confirm(Collection $presales, int $panjang)
    {
        $active_presale = [];
        $site_id = [];
        $company_id = null;
        $wilayah = null;
        $active_presale_last_id = ActivePresale::orderBy('active_presale_id', 'desc')->first();
        $active_presale_last_id = $active_presale_last_id ? $active_presale_last_id->active_presale_id + 1 : 1;
        foreach ($presales as $key => $presale) {
            if (!$company_id) {
                $company_id = $presale->company_id();
                $wilayah = $presale->wilayah();
            }

            $db = DB::table('active_presales')
                ->where('osp_id', $presale->company_id())
                ->where('presale_id', $presale->presale_id)
                ->first();

            if (!$db) {
                $active_presale[] = [
                    'active_presale_id' => $active_presale_last_id,
                    'osp_id' => $presale->company_id(),
                    'presale_id' => $presale->presale_id,
                    'confirmed_by' => Auth::user()->id,
                    'confirmed_at' => date('Y-m-d H:i:s')
                ];

                $active_presale_last_id++;
            }
        }

        $setting = Configuration::find(1);
        $saldo = Saldo::where('company_id', $company_id)->first();
        $total_biaya = $panjang * $setting->kabel_per_meter;

        if ((int) $saldo->saldo > (int)$total_biaya) {

            $deskripsi = "Pembayaran konfirmasi presale sejumlah : " . $presales->count();
            $saldo->decrement('saldo', $total_biaya);
            Saldo::tambah_saldo(1, $total_biaya, $deskripsi);

            ActivePresale::insert($active_presale);

            $this->send_email_konfirmasi($company_id, $total_biaya);
            event(new PresaleIsConfirmed($wilayah));


            $invoice = Invoice::create_invoice_konfirmasi_presale($presale->company_id(), $total_biaya, count($active_presale), $wilayah, 'settlement');

            $invoice_item = [];
            foreach ($active_presale as $key => $active_pres) {
                $invoice_item[] = [
                    'invoice_id' => $invoice->invoice_id,
                    'active_presale_id' => $active_pres['active_presale_id'],
                    'amount' => $total_biaya
                ];
            }


            InvoiceItemKonfirmasiPresale::insert($invoice_item);
            return true;
        }

        return abort(417, trans('presale::presales.messages.payment failed'));
    }

    public function send_email_konfirmasi($company_id, $biaya)
    {
        $emails = (new Company())->admin_email($company_id);

        $data = [
            "title" => trans('presale::presales.mail.confirmation presale.title'),
            "url" => route('admin.saldo.mutasi'),
            'biaya' => $this->rupiah($biaya),
        ];

        Mail::to($emails)->send(new SuccessConfirmationPresale($data));
    }

    public function isActivePresale()
    {
        $company = Auth::user()->company();
        $active_presale = DB::table('active_presales')
            ->where('presale_id', $this->presale_id)
            ->where('osp_id', $company->company_id)
            ->first();
        $active_presale = $active_presale ? $active_presale->active_presale_id : null;
        $this->active_presale_id = $active_presale;

        return !($active_presale == null);
    }

    public function withStatus()
    {
        $this->status_name = StatusPresale::find($this->status_id)->name;
    }

    public function withClass()
    {
        $this->class_name = PresaleClass::find($this->class_id)->class_name;
    }

    public function infowindow()
    {
        $data_status = [

            'status_name' => StatusPresale::find($this->status_id)->name,
            'class_name' => PresaleClass::find($this->class_id)->class_name,
            'hasAccessPresale' => $this->hasAccessPresale()
        ];
        $data = array_merge(
            $this->toArray(),
            $data_status
        );

        return $data;
    }
}
