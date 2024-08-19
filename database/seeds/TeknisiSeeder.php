<?php

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
        // for ($i=1; $i <=5; $i++) {
        //     DB::table('teknisi')->insert([
        //         'company_id' => $i,
        //         'user_id' => $faker->numberBetween(11,20)
        //     ]);
        // }
        for ($i = 23; $i <= 33; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            DB::table('users')->updateOrInsert(
                ['id' => $i],
                [
                    'email' => strtolower($firstName . $lastName) . '@gmail.com',
                    'full_name' => $firstName . " " . $lastName,
                    'phone' => $faker->phoneNumber,
                    'password' => bcrypt('rahasia'),
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );
            DB::table('role_users')->insert([
                'user_id' => $i,
                'role_id' => 4,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            DB::table('user_companies')->insert([
                'user_id' => $i,
                'company_id' => 2,
            ]);
            DB::table('teknisi')->insert([
                'company_id' => 2,
                'user_id' => $i,
            ]);
        }
    }
}
