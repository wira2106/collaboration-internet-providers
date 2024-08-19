<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PengajuanJadwalSeeder extends Seeder
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
            $periode= strtotime('+'.$i.' Weeks');
            $jam= strtotime('+'.$i.' Hours');
            $type = $faker->randomElement(['instalasi','survey']);
            $pengajuanJadwal= DB::table('pengajuan_jadwal')->insert([
                                'pelanggan_id' => $faker->numberBetween(1,10),
                                'keterangan' => $faker->randomElement(['Pembaruan Jadwal','Mengubah Jadwal']),
                                'tgl_rekomendasi' => date('Y-m-d',$periode),
                                'type' => $type,
                                'created_at' => date('Y-m-d',$periode),
                                'created_by' => $faker->numberBetween(1,10)
                            ]);
            $id = DB::getPdo()->lastInsertId();
            if ($type == 'survey') {
                DB::table('tanggal_pengajuan_survey')->insert([
                    'pengajuan_id' => $id,
                    'tgl_survey' => date('Y-m-d',$periode),
                    'jam_survey' => date('H:i',$jam),
                    'status' => $faker->randomElement(['active','not_active'])
                ]);
            } else {
                DB::table('tanggal_pengajuan_instalasi')->insert([
                    'pengajuan_id' => $id,
                    'tgl_instalasi' => date('Y-m-d',$periode),
                    'jam_instalasi' => date('H:i',$jam),
                    'status' => $faker->randomElement(['active','not_active'])
                ]);
            }
            
        }
    }
}
