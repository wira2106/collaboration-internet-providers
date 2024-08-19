<?php

namespace Modules\User\Entities;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Log extends Model
{
    protected $table = "log_activity";
    protected $primaryKey = "log_id";
    protected $fillable = [
        'company_id',
        'user_id',
        'tipe',
        'deskripsi',
        'created_at',
    ];

    public function __construct()
    {
        $user = Auth::user();
        $this->user = $user;
    }

    public function historyLog(Request $request): LengthAwarePaginator
    {

        $company = $this->user->company();
        $roles = DB::table('log_activity')
            ->join('companies', 'log_activity.company_id', '=', 'companies.company_id')
            ->join('users', 'log_activity.user_id', '=', 'users.id')
            ->select([
                'log_activity.*',
                'companies.name as nama_perusahaan',
                'companies.company_id as company_id',
                'users.full_name as nama_user',
                'users.photo_profile as foto'
            ]);
        if ($company->company_id !== 1) {
            $roles = $roles->where('log_activity.company_id', $company->company_id);
        }
        if ($request->get('type') != 'all') {
            $roles = $roles->where('log_activity.tipe', $request->type);
        }
        if($request->get('company_name') != 'all'){
            $roles = $roles->where('companies.company_id', $request->company_name);
        }
        if ($request->get('search') !== null) {
            $term = "%".$request->get('search')."%";            
            $roles = $roles->where(function ($query) use ($term) {
                $query->where('log_activity.deskripsi',  'like', $term)
                      ->orWhere('companies.name',  'like', $term);
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';
            $roles = $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles = $roles->orderBy('log_activity.created_at', 'desc');
        }

        return $roles->paginate($request->get('per_page', 10));
    }

    public function filter($tipe, $deskripsi)
    {
        // dd($tipe,$deskripsi);
        $data = [
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'tipe' => $tipe,
            'deskripsi' => $deskripsi
        ];
        return DB::table('log_activity')->insert($data);
    }
}
