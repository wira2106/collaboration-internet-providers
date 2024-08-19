<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class SettingSeeder extends Seeder
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
            DB::table('setting')->insert([
                'admin_fee' => $faker->numberBetween(25000,50000),
                'persentase_otc_mrc' => $faker->numberBetween(1,10),
                'persentase_refund_osp' => $faker->numberBetween(80,90),
                'persentase_refund_openaccess' => $faker->numberBetween(80,90),
                'sla_odp' => $faker->numberBetween(1,10),
                'sla_join_box' => $faker->numberBetween(1,10),
                'sla_fixing_stack' => $faker->numberBetween(1,10),
            ]);
        }
    }
}
