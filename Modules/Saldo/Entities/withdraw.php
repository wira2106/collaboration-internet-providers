<?php

namespace Modules\Saldo\Entities;

use Illuminate\Http\Request;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class withdraw extends Model
{
    // use Translatable;

    protected $table = 'tarik_saldo';
    protected $primaryKey = 'tarik_saldo_id';
    public $translatedAttributes = [];
    protected $fillable = [
        'company_id',
        'bank_account_id',
        'amount',
        'status',
        'keterangan',
        'bukti_transfer',
        'created_at',
        'created_by',
        'success_on',
        'success_by',
        'expired_at',
        'deleted_at',
        'deleted_by'

    ];

    public function getRekening()
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
            ->where(
                'company_id',
                '=',
                $company->company_id
            )
            ->paginate();
    }

    public function historyWithdraw(Request $request, $status): LengthAwarePaginator
    {
        $company = Auth::user()->company();
        $roles = DB::table('tarik_saldo')
            ->join('bank_account', 'tarik_saldo.bank_account_id', '=', 'bank_account.bank_account_id')
            ->join('companies', 'tarik_saldo.company_id', '=', 'companies.company_id')
            ->join('bank', 'bank_account.bank_id', '=', 'bank.bank_id')
            ->whereNull('tarik_saldo.deleted_at')
            ->select([
                'tarik_saldo.*',
                'companies.name as nama_perusahaan',
                'bank.namaBank as nama_bank_penerima',
                'bank_account.rekening as rekening_penerima',
                'bank_account.atas_nama as atas_nama',
            ]);


        if ($company->company_id !== 1) {
            $roles->where('tarik_saldo.company_id', $company->company_id);
        }
        if ($status != "") {
            $roles->where('tarik_saldo.status', 'LIKE', "%{$status}%");
        }
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $roles->where(function ($query) use ($term) {
                $query->where('tarik_saldo.amount', 'LIKE', "%{$term}%")
                    ->orWhere('tarik_saldo.status', 'LIKE', "%{$term}%")
                    ->orWhere('companies.name', 'LIKE', "%{$term}%")
                    ->orWhere('bank_account.atas_nama', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';
            $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles->orderBy('tarik_saldo.created_at', 'desc');
        }
        return $roles->paginate($request->get('per_page', 10));
    }
}
