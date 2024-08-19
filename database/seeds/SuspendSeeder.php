<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SuspendSeeder extends Seeder
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
            DB::table('suspend')->insert([
                'pelanggan_id' => $faker->numberBetween(1,10),
                'alasan' => $faker->randomElement(['Penunggaan Pembayaran','Terdapat Pelanggaran']),
                'tgl_suspend' => date('Y-m-d',$periode),
                'created_at' => date('Y-m-d',$periode),
                'created_by' => $faker->numberBetween(1,10)
            ]);
        }
    }
}
