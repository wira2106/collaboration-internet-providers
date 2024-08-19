<?php

use Illuminate\Database\Seeder;

class MigrasiWilayahClass extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $db = DB::connection("mysql");
       $openacc = DB::connection("openacc_testing");

       // MIGRASI WILAYAH
       // truncate 
       $db->table("wilayah")->truncate();
       // migrasi wilayah jinom
       $wilayah = $openacc->table("ms_project")->where("idApp",1)->get();
       $insert  = [];       
       foreach ($wilayah as $key => $val) {
          // provinces
          $province = $db->table("provinces")->where("name",$val->provinsi)->first();
          $province_id = $province->id;
          // regencies
          $kabupaten = $db->table("regencies")->where("province_id",$province_id)
                          ->where("name",$val->kabupaten)->first();
          $regencies_id = $kabupaten->id;
          // districts
          $districts = $db->table("districts")->where("regency_id",$regencies_id)
                          ->where("name",$val->kecamatan)->first();
          $district_id = $districts->id;
          // kelurahan
          $villages = $db->table("villages")->where("district_id",$district_id)
                          ->where("name",$val->kelurahan)->first();
          $villages_id = $villages->id;

          $send = [
            "company_id" => 2,
            "name" => $val->namaProject,
            "provinces_id" => $province_id,
            "regencies_id" => $regencies_id,
            "districts_id" => $district_id,
            "villages_id" => $villages_id,
            "post_code" => $val->kodepos,
            "created_at" => $val->created_at,
            "created_by" => 1
          ];
          $insert[] = $send;
       }
       $db->table("wilayah")->insert($insert);

       // MIGRASI CLASS
       $db->table("presale_class")->truncate();
       $class = $openacc->table("ms_class")->get();
       $insert = [];
       foreach ($class as $key => $val) {
         $send = [
              "class_name" => $val->namaClass,
              "icon" => $val->icon,
              "deleted" => $val->deleted
            ];
          $insert[] = $send;
       }
       $db->table("presale_class")->insert($insert);


       // MIGRASI STATUS
       $insert = [
          ["status_id"=>1,"name"=>"Tercover"],
          ["status_id"=>2,"name"=>"Aktif"],
          ["status_id"=>3,"name"=>"Pernah Berlangganan"],
          ["status_id"=>4,"name"=>"Belum Tercover"],
          ["status_id"=>5,"name"=>"Berminat & Belum Tercover"],
          ["status_id"=>6,"name"=>"Blacklist"],
          ["status_id"=>7,"name"=>"VIP"],
        ];       
       $db->table("status_presales")->insert($insert);
    }
}
