<?php

namespace Modules\Saldo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Modules\Saldo\Entities\Interfaces\MutasiSaldoInterface;

class MutasiSaldo extends Model implements MutasiSaldoInterface
{
    protected $table = "mutasi_saldo";
    protected $primaryKey = "mutasi_id";
    protected $fillable = [
        'saldo_id',
        'nominal',
        'tipe',
        'created_at',
        'deskripsi',
    ];

    public $timestamps = false;



    public function getSaldoAwalAkhir($periode,$company_id)
    {    	
    	$tgl_awal 	= $periode."-01";
    	$saldo_awal = $this->getMutasiByPeriode($tgl_awal,$company_id);

    	$tgl_akhir 	 = date('Y-m-01', strtotime('+1 month', strtotime($periode."-01")));
    	$saldo_akhir = $this->getMutasiByPeriode($tgl_akhir,$company_id);

    	$send = [
    		'saldo_awal' => (isset($saldo_awal->saldo) && $saldo_awal->saldo != null? $saldo_awal->saldo:0 ),
    		'saldo_akhir' => (isset($saldo_akhir->saldo) && $saldo_akhir->saldo != null? $saldo_akhir->saldo:0 ),
    	];

    	return $send;
    	
    }


    public function getMutasiByPeriode($periode,$company_id)
    {
    	$saldo = DB::table('mutasi_saldo')
    			->join('saldo','saldo.saldo_id','=','mutasi_saldo.saldo_id')
    			->where('saldo.company_id',$company_id)
    			->groupBy('mutasi_saldo.saldo_id');
    	
    	$select = [
    		DB::raw("(SUM(if(mutasi_saldo.tipe = 'debit',nominal,(nominal*-1)))) as saldo")
    	];
    	$saldo = $saldo->select($select)
    								->where(DB::raw('created_at',"%Y-%m-%d"),'<',$periode)
    								->first();
    	return $saldo;
    }

    public function getMutasiById($id,$company_id)
    {
        $saldo = DB::table('mutasi_saldo')
                ->join('saldo','saldo.saldo_id','=','mutasi_saldo.saldo_id')
                ->where('saldo.company_id',$company_id)
                ->groupBy('mutasi_saldo.saldo_id');
        
        $select = [
            DB::raw("(SUM(if(mutasi_saldo.tipe = 'debit',nominal,(nominal*-1)))) as saldo")
        ];
        $saldo = $saldo->select($select)
                                    ->where('mutasi_id','<=',$id)
                                    ->first();
        return $saldo;
    }

    public static function createMutasi($saldo_id, $nominal, $tipe, $deskripsi)
    {
        self::create([
            'saldo_id' => $saldo_id,
            'nominal' => $nominal,
            'tipe' => $tipe,
            'created_at' => date('Y-m-d H:i:s'),
            'deskripsi' => $deskripsi
        ]);

        return true;
    }
}