<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Media\Support\Traits\MediaRelation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Company\Transformers\RangeBiayaKabelPresaleTransformer;
use Modules\Presale\Entities\Endpoint;
use Modules\Saldo\Entities\Saldo;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Entities\Wilayah;

class Company extends Model
{
    use MediaRelation, SoftDeletes;
    protected $table = 'companies';
    protected $dates = ['deleted_at'];
    protected $primaryKey = "company_id";
    protected $fillable = [
        'name',
        'type',
        'address',
        'pop_address',
        'provinces_id',
        'regencies_id',
        'districts_id',
        'villages_id',
        'post_code',
        'pop_lat',
        'pop_lon',
        'company_lat',
        'company_lon',
        'display_name',
        'total_endpoint',
        'logo_perusahaan',
        'ppn'
    ];
    public $translatedAttributes = [];

    public function getTableName()
    {
        return $this->table;
    }

    public function dayoff()
    {
        return $this->hasMany(DayOff::class, $this->primaryKey);
    }

    public function workingday()
    {
        return $this->hasMany(WorkingDay::class, $this->primaryKey);
    }

    public static function getCompanyUser()
    {
        $company = self::select(["companies.*"])
            ->join("user_companies", "user_companies.company_id", "=", "companies.company_id")
            ->where("user_companies.user_id", Auth::User()->id)
            ->first();

        return $company;
    }

    public function paketberlangganan()
    {
        return $this->belongsToMany('\Modules\Company\Entities\PaketBerlangganan', '\Modules\Company\Entities\PaketForIsp', 'company_id as isp_id');
    }

    public function rating_history(Request $request): LengthAwarePaginator
    {
        $rating = Rating::select(['rating.*', 'users.full_name', 'companies.name'])
            ->join('users', 'users.id', 'rating.pemberi_rating_id')
            ->join('user_companies', 'users.id', 'user_companies.user_id')
            ->join('companies', 'companies.company_id', 'user_companies.company_id')
            ->where('rating.penerima_rating_id', $this[$this->primaryKey]);

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $rating->where(function ($rating) use ($term) {
                $rating->where('users.full_name', 'LIKE', "%{$term}%")
                    ->orWhere('companies.name', 'LIKE', "%{$term}%")
                    ->orWhere('rating.rating', 'LIKE', "%{$term}%")
                    ->orWhere('rating.description', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $rating->orderBy($request->get('order_by'), $order);
        } else {
            $rating->orderBy('rating.created_at', 'desc');
        }

        return $rating->paginate($request->get('per_page', 10));
    }

    public function getRangeBiayaKabel()
    {
        return BiayaKabel::where('company_id', $this->company_id)->orderBy('panjang_kabel')->get();
    }

    public function getSlotInstalasi(Request $request): LengthAwarePaginator
    {
        $slotInstalasi = SlotInstalasi::select(['slot_instalasi.*', 'users.full_name'])
            ->join('users', 'users.id', 'slot_instalasi.created_by')
            ->where("slot_instalasi." . $this->primaryKey, $this[$this->primaryKey]);

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $slotInstalasi->where(function ($slotInstalasi) use ($term) {
                $slotInstalasi->where('users.full_name', 'LIKE', "%{$term}%")
                    ->orWhere('companies.name', 'LIKE', "%{$term}%")
                    ->orWhere('slot_instalasi.jam_start', 'LIKE', "%{$term}%")
                    ->orWhere('slot_instalasi.jam_end', 'LIKE', "%{$term}%");
            });
            
            $order = $request->order === "ascending" ? 'asc' : 'desc';
            $slotInstalasi->orderBy($request->get('order_by'), $order);
        } else {
            $slotInstalasi->orderBy('slot_instalasi.name', 'asc');
        }

        return $slotInstalasi->paginate($request->get('per_page', 10));
    }

    public function updateBiayaKabel(Request $request)
    {
        $company_id = $this[$this->primaryKey];
        try {
            DB::beginTransaction();
            BiayaKabel::where($this->primaryKey, $this[$this->primaryKey])
                ->delete();
            $data = [];
            foreach ($request->all() as $key => $biayaKabel) {
                $data[] = [
                    'company_id' => $company_id,
                    'panjang_kabel' => $biayaKabel['panjang_kabel'],
                    'biaya' => $biayaKabel['biaya'],
                ];
            }
            DB::table('range_biaya_kabel')->insert($data);
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function getAllOSP(User $user)
    {
        if ($user->hasAllAccessCompanies()) {
            return self::where('type', 'osp')->orderBy('name','asc')->get();
        }

        return [];
    }
    public function getAllISP(User $user)
    {
        if ($user->hasAllAccessCompanies()) {
            return self::where('type', 'isp')->orderBy('name','asc')->get();
        }

        return [];
    }

    public function getAllCompany($type = null)
    {
        if(Auth::user()->hasAllAccessCompanies()) {
            $data = DB::table('companies')->whereNull('deleted_at');
            if ($type != 'seluruh') {
                if ($type != "" && $type != null) {
                    $data = $data->where("type", $type);
                }            
            }
            $data = $data->orderBy('name','asc')->get();
    
            return $data;

        }

        return [];
    }
    
    public function getOneOSP() {
        return Company::where('type', 'osp')->first();
    } 

    public function getWilayah(Request $request) {

        if(Auth::user()->getRoleName() === 'Admin ISP') {
            $wilayah = DB::table('request_wilayah')
                        ->leftjoin('wilayah', 'wilayah.wilayah_id', 'request_wilayah.wilayah_id')
                        ->where('isp_id', Auth::user()->userCompanies->company_id)
                        ->whereNull('wilayah.deleted_at')
                        ->where('request_wilayah.status', 'accepted');
            if($request->wilayah_id) $wilayah->where('request_wilayah.wilayah_id', $request->wilayah_id);

            return $wilayah->get();
        }

        $wilayah = Wilayah::select("*");
        $wilayah->where('company_id', $this->company_id);
        if($request->wilayah_id) {
            $wilayah->where('wilayah_id', $request->wilayah_id);
        }
        return $wilayah->get();
    }

    public function validationEndpointName(Request $request) {
        
        $endpoint = Endpoint::select(['end_point.*'])
                            ->join('wilayah', 'wilayah.wilayah_id', 'end_point.wilayah_id')
                            ->where('wilayah.company_id', $this->company_id)
                            ->where('end_point.nama_end_point',$request->name);

        if($request->end_point_id) {
            $endpoint = $endpoint->where('end_point_id', '!=', $request->end_point_id);
        }

        $endpoint = $endpoint->first();

        return $endpoint === null;
    }

    public function getCompanyByType(Request $request) {
        $company = $request->type ? Company::where('type', $request->type)->orderBy('name')->get() : Company::orderBy('name')->get();
        return $company;
    }

    public function biayakabel() {
        $biaya_kabel = BiayaKabel::where('company_id', $this->company_id)->first();

        $response = [];
        
        if($biaya_kabel) {
            $response = [];
            $response['tipe'] = $biaya_kabel->tipe;
            if($biaya_kabel->tipe == 'dropcore') {
                $range_biaya_kabel = RangeBiayaKabel::where('biaya_kabel_id', $biaya_kabel->biaya_kabel_id)->orderBy('panjang_kabel', 'asc')->get();

                $response['dropcore'] = RangeBiayaKabelPresaleTransformer::collection($range_biaya_kabel);
            } else {
                $response['precone'] = $biaya_kabel->harga_per_meter;
            }
        }

        return $response;

    }

    public function getPaket(Request $request, $paket, $company): LengthAwarePaginator
    {

        if ($paket == 'isp') {
            $data = DB::table('paket_for_isp')
                ->join('paket_berlangganan', 'paket_for_isp.paket_id', '=', 'paket_berlangganan.paket_id')
                ->join('companies', 'paket_for_isp.isp_id', '=', 'companies.company_id');
        } else {
            $data =  DB::table('paket_berlangganan')
                ->leftjoin('companies', 'paket_berlangganan.company_id', '=', 'companies.company_id');
        }
        $data = $data
            ->whereNull('paket_berlangganan.deleted_at')
            ->where('paket_berlangganan.company_id', $company->company_id);
        if ($paket == 'option') {
            return $data
                ->orderBy('paket_berlangganan.nama_paket', 'desc')
                ->paginate();
        }
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $data->where('paket_berlangganan.nama_paket', 'LIKE', "%{$term}%")
                ->orWhere('companies.name', 'LIKE', "%{$term}%")
                ->orWhere('paket_berlangganan.paket_id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';
            $data->orderBy($request->get('order_by'), $order);
        } else {
            $data->orderBy('paket_berlangganan.nama_paket', 'asc');
        }
        return $data->paginate($request->get('per_page', 10));
    }

    public function getIspOsp($data)
    {

        return DB::table("companies")
            ->select([
                "companies.company_id",
                "companies.name",
                "companies.type"
            ])
            ->whereNull('deleted_at')
            ->where('companies.type', '=', $data)
            ->get();
    }

    public function getSaldo($company)
    {
        return Saldo::where('company_id', $company->company_id)
            ->first();
    }
    
    public function saldo() {
        return Saldo::where('company_id', $this->company_id)->first();
    }

    public function admin($company_id = null) {
        $company_id = $company_id ? $company_id : $this->company_id;
        
        $users = UserCompanies::where('user_companies.company_id', $company_id)
                                ->join('users', 'users.id', 'user_companies.user_id')
                                ->join('role_users', 'role_users.user_id', 'users.id')
                                ->where(function($query) {
                                    // 1 => Super Admin 
                                    // 2 => Admin OSP
                                    // 3 => Admin ISP
                                    $query->orWhere('role_id', 1)
                                        ->orWhere('role_id', 2)
                                        ->orWhere('role_id', 3);
                                })
                                ->get();

        return $users;
    }

    public function teknisi($company_id) {
        $company_id = $company_id ? $company_id : $this->company_id;

        $users = UserCompanies::where('user_companies.company_id', $company_id)
                                ->join('users', 'users.id', 'user_companies.user_id')
                                ->join('role_users', 'role_users.user_id', 'users.id')
                                ->where('role_id', 4) // 4 => teknisi
                                ->get();

        return $users;
    }

    public function teknisi_email($company_id) {
        $company_id = $company_id ? $company_id : $this->company_id;
        $admins = $this->teknisi($company_id);
        $list_email = [];

        foreach ($admins as $key => $admin) {
            $list_email[] = $admin->email; 
        }

        return $list_email;
    }

    public function admin_email($company_id = null) {
        $company_id = $company_id ? $company_id : $this->company_id;
        $admins = $this->admin($company_id);
        $list_email = [];

        foreach ($admins as $key => $admin) {
            $list_email[] = $admin->email; 
        }

        return $list_email;
    }

    public function admin_isp() {
        $admins = DB::table('companies')
                    ->select(['email', 'companies.name as company_name'])
                    ->where('type', 'isp')
                    ->join('user_companies', 'user_companies.company_id', 'companies.company_id')
                    ->join('users', 'users.id', 'user_companies.user_id')
                    ->join('role_users', 'role_users.user_id', 'users.id')
                    ->where(function($query) {
                        // 1 => Super Admin 
                        // 2 => Admin OSP
                        // 3 => Admin ISP
                        $query->orWhere('role_id', 1)
                              ->orWhere('role_id', 2)
                              ->orWhere('role_id', 3);
                    })
                    ->get();
        
        return $admins;
    }

    public static function getJamKerjaCompanySaatProses($company_id, $created_at)
    {
        $day = date('l', strtotime($created_at));
        $work = DB::table('hari_kerja')->where('company_id',$company_id)->get();
        if($work->count() > 0){
            $data = null;
            foreach ($work as $key => $val) {
                if($day == $val->hari){
                    $data = $val;
                    break;
                }
            }
            if($data != null){
                $start  = strtotime($data->jam_mulai);
                $end    = strtotime($data->jam_selesai);
                $now    = strtotime(date('H:i:s', strtotime($created_at)));
                
                if($start >= $now && $now <= $end){
                    return $created_at;
                }else{
                    $dataSend = null;
                    for ($i=1; $i <= 7 ; $i++) { 
                        $data = null;
                        $day = date('l', strtotime("+".$i." day", strtotime($created_at)));
                        foreach ($work as $key => $val) {
                            if($day == $val->hari){
                                $data = $val;
                                break;
                            }
                        }
                        if($data != null){
                         $dataSend = date('Y-m-d')." ".$data->jam_mulai;
                         break;   
                        }
                    }

                    if($dataSend != null){
                        return $dataSend;
                    }else{
                        return $created_at;
                    }
                }
            }else{
                return $created_at;
            }  
        }else{
            return $created_at;
        }
    }

    public function noc($company_id) {
        $company_id = $company_id ? $company_id : $this->company_id;

        $users = UserCompanies::where('user_companies.company_id', $company_id)
                                ->join('users', 'users.id', 'user_companies.user_id')
                                ->join('role_users', 'role_users.user_id', 'users.id')
                                ->where('role_id', 5) // 5 => NOC
                                ->get();

        return $users;
    }
}