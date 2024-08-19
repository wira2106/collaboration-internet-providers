<?php

namespace Modules\Saldo\Entities;

use Illuminate\Http\Request;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class topup extends Model
{
    // use Translatable;
    use SoftDeletes;
    protected $table = 'topup_saldo';
    protected $primaryKey = 'topup_id';
    // public $translatedAttributes = [];
    protected $fillable = [
        'company_id',
        'bank_id',
        'bank_account_id',
        'amount',
        'rekening_pengirim',
        'atas_nama',
        'bukti_transfer',
        'status',
        'tgl_transfer',
        'keterangan',
        'created_at',
        'created_by',
        'success_on',
        'success_by',
        'expired_at',
        'deleted_at',
        'deleted_by'
    ];

    public function saldo()
    {
        return belongsTo(Saldo::class, 'saldo_id');
    }

    public function getRekeningOpenaccess()
    {
        return DB::table('bank_account')
            ->join('bank', 'bank_account.bank_id', '=', 'bank.bank_id')
            ->select([
                "bank.namaBank",
                "bank.logo",
                "bank_account.bank_account_id",
                "bank_account.atas_nama",
                "bank_account.rekening",
            ])
            ->where('company_id', '=', 1)
            ->whereNull('bank_account.deleted_at')
            ->paginate();
    }

    public function getRekeningPengirim()
    {
        $company = Auth::user()->company();
        return DB::table('bank_account')
            ->join('bank', 'bank_account.bank_id', '=', 'bank.bank_id')
            ->select([
                "bank.namaBank",
                "bank.logo",
                "bank_account.bank_account_id",
                "bank_account.bank_id",
                "bank_account.atas_nama",
                "bank_account.rekening",
            ])
            ->where('company_id', '=', $company->company_id)
            ->whereNull('bank_account.deleted_at')
            ->paginate();
    }

    public function getBank()
    {
        $company = Auth::user()->company();
        return DB::table('bank')->select(["bank.*"])->paginate();
    }

    public function getDetailTopup(Request $request)
    {
        return DB::table('topup_saldo')
            ->join('bank_account', 'topup_saldo.bank_account_id', '=', 'bank_account.bank_account_id')
            ->join('bank', 'topup_saldo.bank_id', '=', 'bank.bank_id')
            ->select(['topup_saldo.*', 'bank_account.*', 'bank_account.*'])
            ->where('topup_id', '=', 'topup_id')
            ->first();
    }

    public function historyTopup(Request $request, $status): LengthAwarePaginator
    {
        $company = Auth::user()->company();
        $roles = DB::table('topup_saldo')
            ->leftJoin('bank as bank_pengirim', 'topup_saldo.bank_id', '=', 'bank_pengirim.bank_id')
            ->join('companies', 'topup_saldo.company_id', '=', 'companies.company_id')
            ->leftJoin('bank_account', 'topup_saldo.bank_account_id', '=', 'bank_account.bank_account_id')
            ->leftJoin('bank as bank_penerima', 'bank_account.bank_id', '=', 'bank_penerima.bank_id')
            ->whereNull('topup_saldo.deleted_at')
            ->select([
                'topup_saldo.*',
                'companies.name as nama_perusahaan',
                'bank_penerima.namaBank as nama_bank_penerima',
                'bank_account.rekening as rekening_penerima',
                'bank_account.atas_nama as nama_penerima',
                'bank_pengirim.namaBank as nama_bank_pengirim'

            ]);
        if ($company->company_id !== 1) {
            $roles->where('topup_saldo.company_id', $company->company_id);
        }
        if ($status != "") {
            $roles->where('topup_saldo.status', 'LIKE', "%{$status}%");
        }
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $roles->where(function ($query) use ($term) {
                $query->where('topup_saldo.amount', 'LIKE', "%{$term}%")
                    ->orWhere('topup_saldo.status', 'LIKE', "%{$term}%")
                    ->orWhere('companies.name', 'LIKE', "%{$term}%")
                    ->orWhere('bank_account.atas_nama', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';
            $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles->orderBy('topup_saldo.topup_id', 'desc');
        }
        return $roles->paginate($request->get('per_page', 10));
    }
}
