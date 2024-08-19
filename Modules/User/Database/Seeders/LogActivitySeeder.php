<?php
namespace Modules\User\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LogActivitySeeder extends Seeder
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
            $tipe = $faker->randomElement(['create data','update data','delete data']);
            $deskripsi = $faker->randomElement([
                    $tipe.' Request Wilayah',
                    $tipe.' Topup saldo',
                    $tipe.' Tarik saldo',
                    $tipe.' Pengajuan Jadwal',
                    $tipe.' Memberi Rating']);
            DB::table('log_activity')->insert([
                'company_id' => $faker->numberBetween(1,10),
                'user_id' => $faker->numberBetween(11,20),
                'tipe' => $tipe,
                'deskripsi' => $deskripsi,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
