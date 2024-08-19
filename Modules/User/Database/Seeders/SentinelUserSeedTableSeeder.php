<?php

namespace Modules\User\Database\Seeders;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SentinelUserSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $company = DB::table('companies')->get();
        foreach ($company as $key => $value) {
            // Create an user
            $insert = [
                    'email' => 'admin@openaccess.net.id',
                    'password' => 'admin',
                    'full_name' => "Admin"
                ];
            if ($value->type == 'isp' || $value->type == 'osp') {
                $insert["email"] = $value->type."_".$value->company_id."@openaccess.net.id";
                $insert["password"] = 'admin';
                $insert["full_name"] = 'admin_'.$value->type.'_'.$value->company_id;
            }

            $user = Sentinel::create($insert);
            // Activate
            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);
            // Find the group using the group id
            if ($value->type == 'isp') {
                $adminGroup = Sentinel::findRoleBySlug('admin isp');
            }else if($value->type == 'osp'){
                $adminGroup = Sentinel::findRoleBySlug('admin osp');
            }else{
                $adminGroup = Sentinel::findRoleBySlug('admin');            
            }

            // Assign the group to the user
            $adminGroup->users()->attach($user);
            DB::table('user_companies')->insert([
                'user_id' => $user->id,
                'company_id' => $value->company_id
            ]);
            app(\Modules\User\Repositories\UserTokenRepository::class)->generateFor($user->id);            
        }


    }
}
