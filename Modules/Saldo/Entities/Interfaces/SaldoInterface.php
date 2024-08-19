<?php

namespace Modules\Saldo\Entities\Interfaces;

interface SaldoInterface 
{
    /**
     * Buat mutasi saldo
     * @param  integer $company_id
     * @param  integer $nominal
     * @param  string $deskripsi
     * 
     * @return bool
     */

     public static function tambah_saldo($company_id, $nominal, $deskripsi);

     /**
     * Buat mutasi saldo
     * @param  integer $company_id
     * @param  integer $nominal
     * @param  string $deskripsi
     * 
     * @return bool
     */

     public static function potong_saldo($company_id, $nominal, $deskripsi);
}