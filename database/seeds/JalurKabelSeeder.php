<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class JalurKabelSeeder extends Seeder
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
            DB::table('jalur_kabel')->insert([
                'presale_id' =>$faker->numberBetween(1,10),
                'lat' => $lat,
                'lon' => $lon
            ]);
        }
    }
}