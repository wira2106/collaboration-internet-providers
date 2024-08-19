<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use EndPointSeeder;
use WilayahSeeder;
use RequestWilayahSeeder;
use KontrakWilayahSeeder;

class WilayahDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call([
        		EndPointSeeder::class,
                WilayahSeeder::class,
                RequestWilayahSeeder::class,
                KontrakWilayahSeeder::class
        	]);
        // $this->call("OthersTableSeeder");
    }
}
