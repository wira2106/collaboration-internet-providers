<?php

namespace Modules\Presale\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use PresalesSeeder;
use PresaleClassSeeder;
use ActivePresalesSeeder;
use JalurKabelSeeder;

class PresaleDatabaseSeeder extends Seeder
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
                PresalesSeeder::class,
                PresaleClassSeeder::class,
                ActivePresalesSeeder::class,
                JalurKabelSeeder::class
            ]);
        // $this->call("OthersTableSeeder");
    }
}
