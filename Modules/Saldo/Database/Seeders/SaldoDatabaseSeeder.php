<?php

namespace Modules\Saldo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use MutasiSaldoSeeder;
use SaldoSeeder;
use TarikSaldoSeeder;
use TopupSaldoSeeder;
class SaldoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            MutasiSaldoSeeder::class,
            SaldoSeeder::class,
            TarikSaldoSeeder::class,
            TopupSaldoSeeder::class
        ]);
    }
}
