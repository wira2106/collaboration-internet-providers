<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TopupSaldoSeeder extends Seeder
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
            $successDate = strtotime('+'.$i.' Days');
            $expiredDate = strtotime('+'.$i.' Weeks');
            DB::table('topup_saldo')->insert([
                'company_id' => $faker->numberBetween(1,10),
                'amount' => $faker->numberBetween(50000,250000),
                'status' => $faker->randomElement(['pending','success','expired']),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' =>  $faker->numberBetween(1,10),
                'success_on' => date('Y-m-d H:i:s',$successDate),
                'success_by' => $faker->numberBetween(1,10),
                'expired_at' => date('Y-m-d H:i:s',$expiredDate),
            ]);
        }
    }
}
