<?php

use Illuminate\Database\Seeder;

class MigrasiEndPoint extends Seeder
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
       $wilayah = $db->table("wilayah")->where("company_id",2)->get();
       $arrWilayah = [];
       $arrIdWilayah = [];
       foreach ($wilayah as $key => $value) {
         $arrWilayah[] = $value->name;
         $arrIdWilayah[] = $value->wilayah_id;
       }

       $db->table("end_point")->truncate();
       $odp = $openacc->table("ms_odp")->select(["ms_odp.*","ms_project.namaProject"])
              ->join("ms_project","ms_project.id","=","ms_odp.idProject")
              ->where("ms_project.idApp",1)
              ->orderBy("ms_odp.id_odp","asc")
              ->get();        

       $insert = [];
       foreach ($odp as $key => $val) {
         $wilayah_id = $arrIdWilayah[array_search($val->namaProject, $arrWilayah)];
         $deleted_at = null;
         if ($val->deleted == 1) {
           $deleted_at = $val->deleted_at;
           if ($deleted_at == null) {
             $deleted_at = date("Y-m-d H:i:s");
           }
         }
         $send = [
            "wilayah_id" => $wilayah_id,
            "nama_end_point" => $val->nama_odp,
            "tipe" => "ODP",
            "lat_end_point" => $val->lat,
            "lon_end_point" => $val->lon,
            "address" => "",
            "created_at" => $val->created_at,
            "created_by" => 1,
            "deleted_at" => $deleted_at
         ];
         $insert[] = $send;
       }
      
      $insert = $db->table("end_point")->insert($insert);
    }
}
