<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KontrakWilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=1; $i <=5 ; $i++) {
            $start = strtotime('+'.$i.' Weeks');
            $end = strtotime('+'.$i.' Months');
            DB::table('kontrak_wilayah')->insert([
                'request_wilayah_id' => $i,
                'start_date' => date('Y-m-d',$start),
                'end_date' => date('Y-m-d',$end),
                'deskripsi_kontrak' => "Syarat dan Ketentuan Berlaku"
            ]);
        }
    }
}
