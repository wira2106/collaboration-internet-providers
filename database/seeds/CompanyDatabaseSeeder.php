<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompanyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
         $faker = Faker::create('id_ID');
         for($i = 1; $i <= 20; $i++){
             
        //Seeder Companies Table
            $type = $faker->randomElement(['isp','osp']);
            $address = $faker->randomElement([
                                'Jl. Kenangan, Gang Hujan',
                                'Jl. Sudirman, Gang Kenangan',
                                'Jl. Gajah Mada, Gang I',
                                'Jl. Mawar Merah, Gang II',
                                'Jl. Kebo Iwa , Gang II'
                            ]);
            $lat = $faker->randomElement([
                                -8.6449336124096,
                                -8.640292656603613,
                                -8.640419941473205,
                                -8.6904893641285,
                                -8.63952714648048
                            ]);
            $lon = $faker->randomElement([
                                115.27310078728,
                                115.27201790367431,
                                115.26824135338134,
                                115.2452269175,
                                115.24990469003
                            ]);
            $name = "PT. ".$faker->firstName." ".$faker->lastName;
            if ($i == 1) {
                $name = "Openaccess";
                $type = null;
            }
            if ($i == 2) {
                $name = "PT Jinom Network Indonesia";
                $type = "osp";
            }

    		DB::table('companies')->insert([
                'name' => $name,
                'type' => $type,
                'provinces_id' => 1,
                'regencies_id' => 1,
                'villages_id' => $faker->numberBetween(1,10),
                'districts_id' => $faker->numberBetween(1,10),
                'address' => $address,
                'post_code' => $faker->postcode,
                'display_name' => $name,
                'logo_perusahaan' => 'default.jpg',
                'pop_lat' => $lat,
                'pop_lon' => $lon,
                'company_lat' => $lat,
                'company_lon' => $lon,
                'pop_address' => $address,
                'total_endpoint' => $faker->numberBetween(1,10),
                'created_at' => date('Y-m-d H:i:s')
    		]);
        
        //Seeder Ijin Usaha Table
            $expired = strtotime('+'.$i.' Months');
            $filename = strtotime('+'.$i.' Days 5 Years');
    		DB::table('ijin_usaha')->insert([
                'company_id' => $i,
                'name' => 'Surat Ijin'.date('Y-m-d',$filename)." ".$name,
                'filename'=>'Surat Ijin'.date('Y-m-d',$filename)." ".$name.'.docx',
                'expired_date'=> date('Y-m-d',$expired)
            ]);
            
        //Seeder Range Biaya Kabel Table (OSP only)
            if ($type == 'osp') {
                // $panjangKabel = $faker->numberBetween(6,20);
                // $biaya= 150000;
                // if ($panjangKabel >= 10) {
                //     $biaya= 250000;
                // }
                // DB::table('range_biaya_kabel')->insert([
                //     'company_id' => $i,
                //     'panjang_kabel'=>$panjangKabel,
                //     'biaya'=>$biaya
                // ]);
                for ($k=0; $k <=1 ; $k++) {
                    $paket = ['Homenet Lite','Homenet Premium'];
                    $hargaPaket =250000;
                    if ($paket == "Homenet Premium") {
                        $hargaPaket =350000;
                    }
                    DB::table('paket_berlangganan')->insert([
                        'company_id' => $i,
                        'nama_paket'=>$paket[$k],
                        'biaya_otc'=>$faker->randomElement(['700000','500000','850000']),
                        'harga_paket'=>$hargaPaket,
                        'sla'=>96,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$faker->numberBetween(1,10)
                    ]);
                }
            }

        //Seeder Rating Table
            $pemberiRating =$faker->numberBetween(1,10);
            $penerimaRating =$faker->numberBetween(1,10);
            
            if ($pemberiRating !== $penerimaRating) {
                DB::table('rating')->insert([
                    'tipe_rating'=> $faker->randomElement(['openaccess','company']),
                    'pemberi_rating_id'=>$pemberiRating,
                    'penerima_rating_id'=>$penerimaRating,
                    'rating'=>$faker->numberBetween(1,10),
                    'description'=>$faker->randomElement(['Pelayanan Bagus','Pelayanan Lumayan Bagus','Bagus ,Terus Ditingkatkan'])
                ]);
            }

         //Seeder hari kerja Table
         $batasHari = $faker->numberBetween(4,5);
         $awalMulai = 8;
         $awalAkhir = $awalMulai+8;
            for ($j=0; $j <= $batasHari; $j++) {
                $hari =['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                $jamMulai =mktime($awalMulai,0);
                $jamSelesai =mktime($awalAkhir,0);
                DB::table('hari_kerja')->insert([
                    'company_id' => $i,
                    'hari'=>$hari[$j],
                    'jam_mulai'=>date('H:i',$jamMulai),
                    'jam_selesai'=>date('H:i',$jamSelesai),
                    'created_at'=>date('Y-m-d H:i:s'),
                    'created_by'=>$faker->numberBetween(1,10)
                ]);
            }

        //Seeder hari libur Table
            $tglLibur =strtotime('+'.$i.' Months '.$i.' weeks');
            DB::table('hari_libur')->insert([
                'company_id' => $faker->numberBetween(1,10),
                'tgl_libur'=>date('Y-m-d',$tglLibur),
                'deskripsi_libur'=>$faker->randomElement(['Libur Hari Raya','Libur Tanggal Merah','Libur Cuti Bersama']),
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$faker->numberBetween(1,10)
            ]);

        

        
        //Seeder Paket for ISP table
            if ($type === "isp") {
            //     DB::table('paket_for_isp')->insert([
            //         'isp_id' => $i,
            //         'paket_id'=>$faker->numberBetween(1,5)
            //     ]);
            //Seeder Diskon Paket Berlangganan
                $diskon = $i * 10;
                $pelanggan_min =$i *5;
                $pelanggan_max =$i *10;
                DB::table('diskon_paket_berlangganan')->insert([
                    'paket_id'=>$faker->numberBetween(1,5),
                    'diskon' =>$diskon,
                    'start_pelanggan'=> $pelanggan_min,
                    'end_pelanggan'=> $pelanggan_max
                ]);
            }
    	}

      $company = DB::table("companies")->get();
      foreach ($company as $key => $value) {
        $ratings = DB::table("rating")->select([DB::raw("AVG(rating) as rating")])->where("penerima_rating_id",$value->company_id)->groupBy('penerima_rating_id')->first();
        if ($ratings != null) {
          DB::table("companies")->where("company_id",$value->company_id)
                                ->update(["rating"=>$ratings->rating]);
        }
      }

    }
}
