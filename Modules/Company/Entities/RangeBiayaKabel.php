<?php

namespace Modules\Company\Entities;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RangeBiayaKabel extends Model
{
    protected $fillable = [
        'biaya_kabel_id',
        'biaya',
        'panjang_kabel'
    ];
    protected $table = "range_biaya_kabel";
    protected $primaryKey = "range_id";
    public $timestamps = false;

    public function serverPaginationFilteringFor(Request $request) : LengthAwarePaginator
    {
        $biaya_kabel_id = $request->biaya_kabel_id;
        $order = $request->order === "ascending" ? 'asc' : 'desc';
        $biaya_kabel = self::where('biaya_kabel_id', $biaya_kabel_id);

        if($request->get('order_by')) {
            $biaya_kabel->orderBy($request->get('order_by', 'panjang_kabel'), $order);
        } else {
            $biaya_kabel->orderBy('panjang_kabel', 'asc');
        }

        // dd($request->get('order_by'));

        
        
        return $biaya_kabel->paginate();
    }

    public function company_id() {
        $biaya_kabel = BiayaKabel::find($this->biaya_kabel_id);
        
        return $biaya_kabel ? $biaya_kabel->company_id : null;
    }

}
