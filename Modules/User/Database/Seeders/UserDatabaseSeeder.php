<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use DB;
use TeknisiSeeder;
use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(TeknisiSeeder::class);
        $faker = Faker::create('id_ID');
        for ($i = 1; $i <= 10; $i++) {
            //Seeder User Table
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
            //Seeder Log Activity User Table
            $tipe = $faker->randomElement(['create data', 'update data', 'delete data']);
            $deskripsi = $faker->randomElement([
                $tipe . ' Request Wilayah',
                $tipe . ' Topup saldo',
                $tipe . ' Tarik saldo',
                $tipe . ' Pengajuan Jadwal',
                $tipe . ' Memberi Rating'
            ]);
            DB::table('log_activity')->insert([
                'company_id' => $faker->numberBetween(1, 10),
                'user_id' => $faker->numberBetween(11, 20),
                'tipe' => $tipe,
                'deskripsi' => $deskripsi,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            //Seeder User Companies Table
            DB::table('user_companies')->insert([
                'user_id' => $i,
                'company_id' => $faker->numberBetween(1, 10),
            ]);
        }
    }
}
