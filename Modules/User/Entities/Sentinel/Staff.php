<?php

namespace Modules\User\Entities\Sentinel;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\UserCompanies;
use Modules\Company\Entities\Company;
use Modules\User\Entities\NOC;
use Modules\User\Entities\Teknisi;
use Modules\Utils\Http\Controllers\ImageController;

class Staff extends Model
{
  public function saveFormStaff($req)
  {
    $log = Auth::user();
    $user_id = $req->user_id;
    if ($req->company_id == null) {
      $company = Auth::User()->company();
    } else {
      $company = Company::find($req->company_id);
    }

    // upload foto
    $filename = "";
    $photo_profile = $req->file("photo_profile");
    if ($photo_profile != "" && $photo_profile != null) {
      $filename = ImageController::uploadFoto($photo_profile);
    }

    if ($user_id != null) {
      // update data
      $log->createLog(
        trans('user::staff.logs.edit.tipe'),
        trans('user::staff.logs.edit.description', [
          'data' => $req->full_name
        ])
      );
      $update = [
        'email' => $req->email,
        'full_name' => $req->full_name,
        'phone' => $req->phone,
      ];
      if ($filename != "") {
        $update["photo_profile"] = $filename;
      }
      if ($req->password != "" && $req->password != null) {
        $update["password"] = bcrypt($req->password);
      }
      DB::table("users")->where("id", $user_id)->update($update);
    } else {
      // add data
      $log->createLog(
        trans('user::staff.logs.create.tipe'),
        trans('user::staff.logs.create.description', [
          'data' => $req->full_name
        ])
      );
      $insert = [
        'email' => $req->email,
        'password' => $req->password,
        'full_name' => $req->full_name,
        'phone' => $req->phone,
      ];
      if ($filename != "") {
        $insert["photo_profile"] = $filename;
      }
      $user = Sentinel::create($insert);
      // Activate the admin directly
      $activation = Activation::create($user);
      Activation::complete($user, $activation->code);

      // Find the group using the group id
      if ($company->type == 'isp') {
        $adminGroup = $this->assignRoleISP($req, $company, $user);
      } else if ($company->type == 'osp') {
        $adminGroup = $this->assignRoleOSP($req, $company, $user);
      } else {
        $adminGroup = $this->assignRoleOpenaccess($req, $company, $user);
      }

      // Assign the group to the user
      $adminGroup->users()->attach($user);

      UserCompanies::create([
        'user_id' => $user->id,
        'company_id' => $company->company_id
      ]);

      app(\Modules\User\Repositories\UserTokenRepository::class)->generateFor($user->id);

      //mengecek role dari user yang baru ditambahkan untuk kepentingan 
      return [
        'user_id'=>$user->id
      ];
    }
  }

  private function assignRoleISP($req, $company, $user)
  {
    if ($req->role === 'admin') {
      $adminGroup = Sentinel::findRoleBySlug('admin isp');
    } else if ($req->role === 'teknisi') {
      $adminGroup = Sentinel::findRoleBySlug('teknisi');
      Teknisi::create([
        'company_id' => $company->company_id,
        'user_id' => $user->id
      ]);
    } else {
      $adminGroup = Sentinel::findRoleBySlug('noc');
      NOC::create([
        'company_id' => $company->company_id,
        'user_id' => $user->id
      ]);
    }

    return $adminGroup;
  }

  private function assignRoleOSP($req, $company, $user)
  {
    if ($req->role === 'admin') {
      $adminGroup = Sentinel::findRoleBySlug('admin osp');
    } else if ($req->role === 'teknisi') {
      $adminGroup = Sentinel::findRoleBySlug('teknisi');
      Teknisi::create([
        'company_id' => $company->company_id,
        'user_id' => $user->id
      ]);
    } else {
      $adminGroup = Sentinel::findRoleBySlug('noc');
      NOC::create([
        'company_id' => $company->company_id,
        'user_id' => $user->id
      ]);
    }

    return $adminGroup;
  }

  private function assignRoleOpenaccess($req, $company, $user)
  {
    if ($req->role === 'admin') {
      $adminGroup = Sentinel::findRoleBySlug('admin');
    } else if ($req->role === 'teknisi') {
      $adminGroup = Sentinel::findRoleBySlug('teknisi');
      Teknisi::create([
        'company_id' => $company->company_id,
        'user_id' => $user->id
      ]);
    } else {
      $adminGroup = Sentinel::findRoleBySlug('noc');
      NOC::create([
        'company_id' => $company->company_id,
        'user_id' => $user->id
      ]);
    }

    return $adminGroup;
  }

  public function detailStaff($user_id)
  {
    $data = User::join("user_companies", "user_companies.user_id", "=", "users.id")
      ->select(["users.*", "user_companies.company_id"])
      ->where("users.id", $user_id)
      ->first();

    return $data;
  }
}