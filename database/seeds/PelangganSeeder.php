<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $faker = Faker::create('id_ID');
        for ($i=1; $i <=10.; $i++) {
            $paket=$faker->numberBetween(1,2);
            if ($paket == 1) {
                $mrc = 250000;
                $otc = 850000;
            } else {
                $mrc = 350000;
                $otc = 500000;
            }
            
            DB::table('pelanggan')->insert([
                'presale_id' => $faker->numberBetween(1,10),
                'isp_id' => $faker->numberBetween(1,10),
                'paket_id' => $paket,
                'biaya_mrc' => $mrc,
                'biaya_otc' => $otc,
                'nama_pelanggan' => $faker->name,
                'telepon' => $faker->phoneNumber,
                'email' => $faker->safeEmail,
                'status_kepemilikan' => $faker->randomElement(['pemilik','penyewa','lainnya']),
                'jenis_identitas' => "KTP",
                'nomor_identitas' => $faker->nik,
                'status' => $faker->randomElement(['so','survey','verifikasi','instalasi','aktivasi','aktif','cancel']),
                'suspended_at' => null,
                'created_at' => date('Y-m-d')
            ]);
        }
    }
}
