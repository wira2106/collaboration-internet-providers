<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MutasiSaldoSeeder extends Seeder
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
            $date = strtotime('+'.$i.' Weeks');
            DB::table('mutasi_saldo')->insert([
                'saldo_id' =>$faker->numberBetween(1,10),
                'nominal' => $faker->numberBetween(250000,500000),
                'tipe'=> $faker->randomElement(['debit','credit']),
                'created_at' => date('Y-m-d H:i:s', $date),
                'created_at' => $faker->numberBetween(1,10)
            ]);
        }
    }
}
