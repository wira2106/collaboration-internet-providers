<?php

namespace Modules\Saldo\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Saldo\Entities\Interfaces\SaldoInterface;

class Saldo extends Model implements SaldoInterface
{
    protected $table = "saldo";
    protected $primaryKey = 'saldo_id';
    protected $fillable = [
        'company_id',
        'saldo',
        'dibekukan'
    ];

    public $timestamps = false;

    public static function tambah_saldo($company_id, $nominal, $deskripsi)
    {
        $saldo = self::where('company_id', $company_id)->first();
        $saldo->increment('saldo', $nominal);

        return MutasiSaldo::createMutasi($saldo->saldo_id, $nominal, 'debit', $deskripsi);
    }

    public static function tambah_saldo_dibekukan($company_id, $nominal, $deskripsi)
    {
        $saldo = self::where('company_id', $company_id)->first();
        $saldo->increment('saldo', $nominal);
        $saldo->increment('dibekukan', $nominal);

        return MutasiSaldo::createMutasi($saldo->saldo_id, $nominal, 'debit', $deskripsi);
    }

    public static function potong_saldo($company_id, $nominal, $deskripsi)
    {
        $saldo = self::where('company_id', $company_id)->first();
        $saldo->decrement('saldo', $nominal);

        return MutasiSaldo::createMutasi($saldo->saldo_id, $nominal, 'credit', $deskripsi);
    }
    public static function potong_saldo_dibekukan($company_id, $nominal)
    {
        $saldo = self::where('company_id', $company_id)->first();
        $saldo->decrement('dibekukan', $nominal);
    }
}
