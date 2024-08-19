<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PerubahanHargaSeeder extends Seeder
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
            $periode= strtotime('+'.$i.' Weeks');
            DB::table('perubahan_harga')->insert([
                'harga_otc' => $faker->numberBetween(500000,800000),
                'harga_mrc' => $faker->numberBetween(250000,350000),
                'status' => $faker->randomElement(['request','accepted','rejected']),
                'keterangan' => "Terdapat Perubahan Harga",
                'created_at' => date('Y-m-d',$periode),
                'created_by' => $faker->numberBetween(1,10)
            ]);
        }
    }
}
