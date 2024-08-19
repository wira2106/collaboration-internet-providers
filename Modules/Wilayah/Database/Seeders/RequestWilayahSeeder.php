<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RequestWilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=1; $i <=10 ; $i++) {
            DB::table('request_wilayah')->insert([
                'isp_id' => $faker->numberBetween(1,10),
                'osp_id' => $faker->numberBetween(1,5),
                'wilayah_id' => $faker->numberBetween(1,5),
                'status' => $faker->randomElement(['request','confirmed','rejected']),
                'alasan' => $faker->randomElement(['Menambah Cakupan Penjualan','Melakukan Penarikan Jaringan']),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
