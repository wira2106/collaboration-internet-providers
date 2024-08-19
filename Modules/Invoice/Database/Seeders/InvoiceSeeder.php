<?php
namespace Modules\Invoice\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=1; $i <=10; $i++) {
            $periode= strtotime('+'.$i.' Weeks');
            DB::table('invoice')->insert([
                'isp_id' => $faker->numberBetween(1,5),
                'osp_id' => $faker->numberBetween(6,10),
                'periode' => date('Y-m-d',$periode),
                'status' => $faker->randomElement(['pending','setlement']),
                'settlement_at' => date('Y-m-d',$periode)
            ]);
        }
    }
}
