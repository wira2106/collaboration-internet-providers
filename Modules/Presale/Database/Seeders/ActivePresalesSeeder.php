<?php
namespace Modules\Presale\Database\Seeders;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ActivePresalesSeeder extends Seeder
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
            $confirm =strtotime('+'.$i.' Weeks');
            DB::table('active_presales')->insert([
                'osp_id'=>$faker->numberBetween(1,10),
                'presale_id'=>$faker->numberBetween(1,10),
                'confirmed_by' =>$faker->numberBetween(1,5),
                'confirmed_at'=> date('Y-m-d H:i:s',$confirm)
            ]);
        }
    }
}
