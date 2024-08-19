<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 10; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            DB::table('users')->insert([
                'email' => strtolower($firstName . $lastName) . '@gmail.com',
                'full_name' => $firstName . " " . $lastName,
                'phone' => $faker->phoneNumber,
                'password' => bcrypt('rahasia'),
                'permissions' => 'user',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
