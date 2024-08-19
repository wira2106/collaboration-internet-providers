<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PresalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i=1; $i <=5 ; $i++) {
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
                 
            DB::table('presales')->insert([
                'site_id' =>$faker->numberBetween(1,10),
                'wilayah_id' =>$faker->numberBetween(1,10),
                'end_point_id' =>$faker->numberBetween(1,10),
                'class_id' =>$faker->numberBetween(1,5),
                'panjang_kabel' =>$faker->numberBetween(6,20),
                'lat' => $lat,
                'lon' => $lon,
                'provinces_id' => 1,
                'districts_id' => $faker->numberBetween(1,10),
                'regencies_id' => 1,
                'villages_id' => $faker->numberBetween(1,10),
                'address' => $address,
                'foto_rumah' => '00000.jpg',
                'nama_gang' => $address,
                'no_rumah' => $faker->numberBetween(1,10),
                'kode_pos' => $faker->postcode,
                'biaya_kabel' => $faker->numberBetween(25000,80000),
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$faker->numberBetween(1,5)
            ]);
        }
    }
}
