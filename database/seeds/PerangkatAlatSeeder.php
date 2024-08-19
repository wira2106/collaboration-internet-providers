<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PerangkatAlatSeeder extends Seeder
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
            DB::table('perangkat')->insert([
                'nama_perangkat' => $faker->randomElement(['TP-Link P','Mikrotik P']).$i,
                'deleted_at' => 0
            ]);
            DB::table('alat')->insert([
                'nama_alat' => $faker->randomElement(['TP-Link A','Mikrotik A']).$i,
                'deleted_at' => 0
            ]);
        }
    }
}
