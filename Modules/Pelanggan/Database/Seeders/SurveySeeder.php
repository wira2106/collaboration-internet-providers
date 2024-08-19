<?php
namespace Modules\Pelanggan\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SurveySeeder extends Seeder
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
            $status =$faker->randomElement(['active','finish','reschedule','cancel']);
            $keterangan = "Survey dalam status ".$status;
            DB::table('survey')->insert([
                'pelanggan_id' => $i,
                'tgl_survey' => date('Y-m-d',$periode),
                'tinggi_bangunan' => $faker->numberBetween(1,3),
                'satuan_tinggi' => $faker->randomElement(['meter','lantai']),
                'foto_jalur_kabel' =>  "jalur.jpg",
                'foto_signal_kabel' =>  "kabel.jpg",
                'keterangan' =>  $keterangan,
                'status' => $status
            ]);
            $hargaBarangSatuan=numberBetween(35000,85000);
            $qty=$faker->numberBetween(1,10);
            $hargaBarangSeluruh=$hargaBarangSatuan*$qty;
            DB::table('barang_tambahan')->insert([
                'survey_id' => $faker->numberBetween(1,10),
                'barang_id' => $faker->numberBetween(1,10),
                'harga_per_pcs' => $hargaBarangSatuan,
                'qty'=>$qty,
                'harga'=>$hargaBarangSeluruh
            ]);
            DB::table('barang')->insert([
                'nama_barang' => $faker->numberBetween(1,10),
                'tipe_qty' => $qty,
                'harga_per_pcs' =>$hargaBarangSatuan,
                'deleted' => 0
            ]); 
        }
    }
}
