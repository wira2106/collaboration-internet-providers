<?php
namespace Modules\Wilayah\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EndPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $faker = Faker::create('id_ID');
        for ($i=1; $i <=10 ; $i++) {
            $address = $faker->randomElement([
                                'Jl. Kenangan, Gang Hujan',
                                'Jl. Sudirman, Gang Kenangan',
                                'Jl. Gajah Mada, Gang I',
                                'Jl. Mawar Merah, Gang II',
                                'Jl. Kebo Iwa , Gang II'
                            ]);
            $endPointName = $faker->randomElement([
                        'End Point Gianyar '.$i,
                        'End Point Tegallalang '.$i,
                        'End Point Payangan '.$i,
                        'End Point Tampaksiring '.$i,
                        'End Point Ubud '.$i,
                        'End Point Sukawati '.$i,
                        'End Point Blahbatuh '.$i]);
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
            DB::table('end_point')->insert([
                'wilayah_id'=>$faker->numberBetween(1,5),
                'nama_end_point' =>$endPointName,
                'tipe' => $faker->randomElement(['Fixing Slack','JB','ODP']),
                'lat_end_point' => $lat,
                'lon_end_point' => $lon,
                'address' => $address,
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$faker->numberBetween(1,5)
            ]);
        }
    }
}
