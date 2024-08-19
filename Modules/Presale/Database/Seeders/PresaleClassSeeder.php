<?php
namespace Modules\Presale\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PresaleClassSeeder extends Seeder
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
            DB::table('presale_class')->insert([
                'class_name'=>'Class Presale '.$i,
                'icon'=>'Icon Class Presale '.$i,
            ]);
        }
    }
}
