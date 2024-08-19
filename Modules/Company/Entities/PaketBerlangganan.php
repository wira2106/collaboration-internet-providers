<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaketBerlangganan extends Model
{
    // use Translatable;
    use SoftDeletes;
    protected $table = 'paket_berlangganan';
    protected $primaryKey = 'paket_id';
    public $translatedAttributes = [];
    protected $fillable = [
        'paket_id',
        'company_id',
        'nama_paket',
        'biaya_otc',
        'harga_paket',
        'sla',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];

    public function company()
    {
        return $this->belongsToMany('\Modules\Company\Entities\PaketBerlangganan', '\Modules\Company\Entities\PaketForIsp', 'paket_id');
    }

    public function getAllDiscount()
    {
        $diskon = DB::table('diskon_paket_berlangganan')
            ->where('paket_id', $this->paket_id)->orderBy('start_pelanggan', 'asc')->get();

        foreach ($diskon as $key => $value) {
            $value->harga_paket = $this->harga_paket;
        }
        return $diskon;
    }

    public function getDiscount(Request $request, $id): LengthAwarePaginator
    {
        $roles =  DB::table('diskon_paket_berlangganan')
            ->join('paket_berlangganan', 'diskon_paket_berlangganan.paket_id', '=', 'paket_berlangganan.paket_id');
        $roles = $roles
            ->whereNull('paket_berlangganan.deleted_at')
            ->where('paket_berlangganan.paket_id', $id);
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $roles->where('paket_berlangganan.nama_paket', 'LIKE', "%{$term}%")
                ->orWhere('companies.name', 'LIKE', "%{$term}%")
                ->orWhere('paket_berlangganan.paket_id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles->orderBy('diskon_paket_berlangganan.start_pelanggan', 'asc');
        }

        return $roles->paginate($request->get('per_page', 10));
    }
}
