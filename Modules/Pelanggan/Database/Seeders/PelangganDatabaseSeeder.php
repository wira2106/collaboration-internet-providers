<?php

namespace Modules\Pelanggan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use AktivasiSeeder;
use InstalasiSeeder;
use PelangganSeeder;
use PengajuanJadwalSeeder;
use PerubahanHargaSeeder;
use SurveySeeder;
use SuspendSeeder;
use TicketSupportSeeder;
class PelangganDatabaseSeeder extends Seeder
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
            AktivasiSeeder::class,
            InstalasiSeeder::class,
            PelangganSeeder::class,
            PengajuanJadwalSeeder::class,
            PerubahanHargaSeeder::class,
            SurveySeeder::class,
            SuspendSeeder::class,
            TicketSupportSeeder::class
        ]);
        // $this->call("OthersTableSeeder");
    }
}
