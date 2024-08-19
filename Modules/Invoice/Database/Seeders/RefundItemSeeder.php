<?php
namespace Modules\Invoice\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RefundItemSeeder extends Seeder
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
            DB::table('refund_item')->insert([
                'invoice_item_id' => $i,
                'refund_amount' => $faker->numberBetween(60000,350000),
                'refund_amount' => $faker->randomElement(['sla','suspend']),
                'description' => " - ",
                'created_at' => date('Y-m-d',$periode)
            ]);
        }
    }
}
