<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BiayaKabel extends Model
{
    protected $fillable = [
        'tipe',
        'harga_per_meter',
        'company_id'
    ];
    protected $table = "biaya_kabel";
    protected $primaryKey = "biaya_kabel_id";
    public $timestamps = false;

    public function getBiayaKabel(Request $request) 
    {
        $biaya_kabel = self::where('company_id', $request->company_id)->first();
        
        if(!$biaya_kabel) {
            $biaya_kabel = self::create([
                'company_id' => $request->company_id,
                'tipe' => 'precone',
                'harga_per_meter' => 0,
            ]);
        }

        return $biaya_kabel;
    }

    
}
