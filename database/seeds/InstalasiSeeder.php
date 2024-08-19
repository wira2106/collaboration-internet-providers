<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InstalasiSeeder extends Seeder
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
            $end = $i+3;
            $periode= strtotime('+'.$i.' Weeks');
            $jamStart= strtotime('+'.$i.' Hours');
            $jamEnd= strtotime('+'.$end.' Hours');
            $status =$faker->randomElement(['active','finish','reschedule','cancel']);
            $keterangan = "Instalasi dalam status ".$status;
            DB::table('instalasi')->insert([
                'pelanggan_id' => $i,
                'tgl_instalasi' => date('Y-m-d',$periode),
                'jam_start'=> date('H:i',$jamStart),
                'jam_stop'=> date('H:i',$jamEnd),
                'keterangan' =>  $keterangan,
                'status' => $status
            ]);
            DB::table('perangkat_instalasi')->insert([
                'instalasi_id' => $faker->numberBetween(1,10),
                'perangkat_id' => $faker->numberBetween(1,10),
                'jenis_perangkat' => $faker->randomElement(['ONT','akses point'])
            ]);
            DB::table('alat_instalasi')->insert([
                'instalasi_id' => $faker->numberBetween(1,10),
                'alat_id' => $faker->numberBetween(1,10)
            ]);
            DB::table('instalation_staff')->insert([
                'instalasi_id' => $faker->numberBetween(1,10),
                'user_id' => $faker->numberBetween(1,10)
            ]);
            DB::table('slot_instalasi')->insert([
                'company_id' => $faker->numberBetween(1,10),
                'name' => 'Slot '.$i,
                'jam_start'=> date('H:i',$jamStart),
                'jam_end'=> date('H:i',$jamEnd),
                'created_at'=>date('Y-m-d H:i:s',$jamEnd),
                'created_by'=> $faker->numberBetween(1,10)
            ]);
           
            
        }
    }
}
