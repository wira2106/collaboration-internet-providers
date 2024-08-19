<?php
namespace Modules\User\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TeknisiSeeder extends Seeder
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
            DB::table('teknisi')->insert([
                'company_id' => $i,
                'user_id' => $faker->numberBetween(11,20)
            ]);
        }
    }
}
