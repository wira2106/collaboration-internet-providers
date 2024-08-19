<?php

use Illuminate\Database\Seeder;
use Modules\User\Database\Seeders\SentinelGroupSeedTableSeeder;
use Modules\User\Database\Seeders\SentinelUserSeedTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CompanyDatabaseSeeder::class,
            InvoiceItemSeeder::class,
            InvoiceSeeder::class,
            RefundItemSeeder::class,
            AktivasiSeeder::class,
            InstalasiSeeder::class,
            PelangganSeeder::class,
            PengajuanJadwalSeeder::class,
            PerubahanHargaSeeder::class,
            SurveySeeder::class,
            SuspendSeeder::class,
            TicketSupportSeeder::class,
            PerangkatAlatSeeder::class,
            PresalesSeeder::class,
            PresaleClassSeeder::class,
            ActivePresalesSeeder::class,
            JalurKabelSeeder::class,
            MutasiSaldoSeeder::class,
            SaldoSeeder::class,
            TarikSaldoSeeder::class,
            TopupSaldoSeeder::class,
            TeknisiSeeder::class,
            LogActivitySeeder::class,
            EndPointSeeder::class,
            WilayahSeeder::class,
            RequestWilayahSeeder::class,
            KontrakWilayahSeeder::class,
            SettingSeeder::class,
            SentinelGroupSeedTableSeeder::class,
            SentinelUserSeedTableSeeder::class,
            MigrasiWilayahClass::class,
            MigrasiEndPoint::class,
            MigrasiPresales::class
        ]);
    }
}
