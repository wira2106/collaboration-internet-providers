<?php
namespace Modules\User\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserCompaniesSeeder extends Seeder
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
            DB::table('user_companies')->insert([
                'user_id' => $i,
                'company_id' => $faker->numberBetween(1,10),
            ]);
        }
    }
}
