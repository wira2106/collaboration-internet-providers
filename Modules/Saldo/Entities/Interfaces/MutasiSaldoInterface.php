<?php

namespace Modules\Saldo\Entities\Interfaces;

interface MutasiSaldoInterface 
{
    /**
     * Buat mutasi saldo
     * @param  integer $saldo_id
     * @param  integer $nominal
     * @param  enum $tipe ('debit', 'credit')
     * @param  string $deskripsi
     * 
     * @return bool
     */

     public static function createMutasi($saldo_id, $nominal, $tipe, $deskripsi);
}