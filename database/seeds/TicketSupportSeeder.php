<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TicketSupportSeeder extends Seeder
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
            $periode= strtotime('+'.$i.' Hours');
            $keterangan = $faker->randomElement(['Masalah pada kabel FO','Masalah pada Perangkat Wireless','FO Putus','Gangguan Pada Jaringan']);
            DB::table('ticket_support')->insert([
                'pelanggan_id' => $i,
                'keterangan' => $keterangan,
                'start_gangguan' => date('Y-m-d H:i:s'),
                'end_gangguan' => date('Y-m-d H:i:s',$periode),
                'accept_osp_by' =>1,
                'created_at' => date('Y-m-d H:i:s',$periode),
                'created_by' => $faker->numberBetween(2,10)
            ]);
            DB::table('ticket_message')->insert([
                'ticket_id' => $i,
                'message' => $keterangan,
                'osp_id' =>$faker->numberBetween(2,10),
                'isp_id' =>$faker->numberBetween(2,10),
                'unsend' =>1,
                'created_at' => date('Y-m-d H:i:s',$periode),
                'created_by' => $faker->numberBetween(2,10)
            ]);
        }
    }
}
