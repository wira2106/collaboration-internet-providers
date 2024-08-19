<?php

use Illuminate\Database\Seeder;

class MigrasiPresales extends Seeder
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

       // truncate table
       $db->table("presales")->truncate();
       $db->table("jalur_kabel")->truncate();

       // wilayah
       $wilayah = $db->table("wilayah")->where("company_id",2)->get();
       $arrWilayah = [];
       $arrIdWilayah = [];
       foreach ($wilayah as $key => $value) {
         $arrWilayah[] = $value->name;
         $arrIdWilayah[] = $value->wilayah_id;
       }
       // end point
       $ep = $db->table("end_point")->where("deleted_at","=",null)->get();
       $arrEp = [];
       $arrIdEp = [];
       foreach ($ep as $key => $value) {
         $arrEp[] = $value->nama_end_point;
         $arrIdEp[] = $value->end_point_id;
       }

       $presales = $openacc->table("tr_presale")
                    ->select(["tr_presale.*","ms_project.namaProject","ms_odp.nama_odp"])
                    ->join("ms_project","ms_project.id","=","tr_presale.idProject")
                    ->join("ms_odp","tr_presale.id_odp","=","ms_odp.id_odp")
                    ->where("ms_project.idApp",1)
                    ->orderBy("tr_presale.id","asc")
                    ->get();
       $line = $openacc->table("tr_lines_presale")->orderBy("id_presale","asc")->get();       
       $lines = [];
       foreach ($line as $key => $val) {
         if (isset($lines[$val->id_presale]) && count($lines[$val->id_presale]) == 0) {
           $lines[$val->id_presale] = [];
         }
         $lines[$val->id_presale][] = ["lat"=>$val->lat,"lon"=>$val->lon];
       }

       $insertPresales = [];
       $insertKabel = [];
       foreach ($presales as $key => $val) {
        $deleted_at = null;
        if ($val->deleted == 1) {
            
           $deleted_at = $val->deleted_at;
           if ($deleted_at == null) {
             $deleted_at = date("Y-m-d H:i:s");
           }
         }
          $arr = [
              "site_id" => $val->siteID,
              "wilayah_id" => $arrIdWilayah[array_search($val->namaProject, $arrWilayah)],
              "end_point_id" => $arrIdEp[array_search($val->nama_odp, $arrEp)],
              "class_id" => $val->id_class,
              "panjang_kabel" => $val->jarak_odp,
              "lat" => $val->lat,
              "lon" => $val->lon,
              "provinces_id" => 1,
              "districts_id" => 2,
              "regencies_id" => 3,
              "villages_id" => 4,
              "address" => $val->alamat,
              "foto_rumah" => $val->fotoRumah,
              "nama_gang" => $val->nama_gang,
              "no_rumah" => $val->no_rumah,
              "kode_pos" => $val->kodePOS,
              "biaya_kabel" => $val->biaya_kabel,
              "created_at" => $val->created_at,
              "created_by" => 1,
              "status_id" => ($val->status+1),
              "biaya_instalasi" => $val->biaya_instalasi,
              "total_biaya" => $val->totalBiaya,
              "keterangan" => $val->keterangan,
              "deleted_at" => $deleted_at
          ];

          $insertPresales[] = $arr;
          if (isset($lines[$val->id])) {
            $kabl = $lines[$val->id];
            for ($i=0; $i < count($kabl) ; $i++) { 
              $insertKabel[] = [
                "presale_id" => $key+1,
                "lat" => $kabl[$i]["lat"],
                "lon" => $kabl[$i]["lon"]
              ];                          
            }
          }
       }

       foreach (array_chunk($insertPresales,1000) as $t)  
       {
            $db->table("presales")->insert($t);
       }
       foreach (array_chunk($insertKabel,1000) as $d)  
       {            
            $db->table("jalur_kabel")->insert($d);
       }


       $wilayah = $db->table("wilayah")->where("company_id",2)->get();
       foreach ($wilayah as $key => $value) {
        $update = [
                  "provinces_id"=>$value->provinces_id,
                  "regencies_id"=>$value->regencies_id,
                  "districts_id"=>$value->districts_id,
                  "villages_id"=>$value->villages_id
                ];
         $db->table("presales")->where("wilayah_id",$value->wilayah_id)->update($update);
       }
    }
}
