<?php

namespace Modules\Peralatan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use PerangkatAlatSeeder;
class PeralatanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(PerangkatAlatSeeder::class);
        // $this->call("OthersTableSeeder");
    }
}
