<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class WilayahSeeder extends Seeder
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
            DB::table('wilayah')->insert([
                'company_id'=>$faker->numberBetween(1,10),
                'name' =>$faker->randomElement(['Wilayah Gianyar '.$i,'Wilayah Tegallalang '.$i,'Wilayah Payangan '.$i,'Wilayah Tampaksiring '.$i,'Wilayah Ubud '.$i,'Wilayah Sukawati '.$i,'Wilayah Blahbatuh '.$i]),
                'provinces_id' => 1,
                'regencies_id' => 1,
                'districts_id' => $faker->numberBetween(1,10),
                'villages_id' => $faker->numberBetween(1,10),                
                'post_code' => $faker->postcode,
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$faker->numberBetween(1,5)
            ]);
        }
    }
}
