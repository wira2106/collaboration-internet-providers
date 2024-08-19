<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InvoiceItemSeeder extends Seeder
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
            DB::table('invoice_item')->insert([
                'invoice_id' => $faker->numberBetween(1,5),
                'pelanggan_id' => $faker->numberBetween(6,10),
                'amount' => $faker->numberBetween(60000,350000)
            ]);
        }
    }
}
