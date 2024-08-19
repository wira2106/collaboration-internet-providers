<?php
namespace Modules\Saldo\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SaldoSeeder extends Seeder
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
            
            DB::table('saldo')->insert([
                'company_id' =>$i,
                'saldo' => $faker->numberBetween(250000,500000)
            ]);
        }
    }
} 