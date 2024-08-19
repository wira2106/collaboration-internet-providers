<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AktivasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=1; $i <=5; $i++) {
            $tglInstalasi = strtotime('+'.$i.'Weeks 1 Months');
            DB::table('aktivasi')->insert([
                'pelanggan_id' => $i,
                'noc' => $faker->numberBetween(1,10),
                'tgl_aktivasi' => date('Y-m-d H:i:s',$tglInstalasi),
                'keterangan' => "Proses Aktivasi Telah dilakukan"
            ]);
        }
    }
}
