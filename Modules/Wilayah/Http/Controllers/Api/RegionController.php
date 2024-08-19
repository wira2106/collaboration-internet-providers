<?php

namespace Modules\Wilayah\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function provinces(Request $request) {
        $data = $this->jsonProvinsi();
        return $data;
    }

    public function regencies(Request $request) {
        $id = $request->get("provinces_id");
        $data = DB::table('regencies')
                ->where('province_id', $id)
                ->get();
        return $data;
    }
    public function districts(Request $request) {
        $id = $request->get("regencies_id");
        $data = DB::table('districts')
                ->where('regency_id', $id)
                ->get();
        return $data;
    }

    public function villages(Request $request) {
        $id = $request->get("districts_id");
        $data = DB::table('villages')
                ->where('district_id', $id)
                ->get();
        return $data;
    }

    public function jsonProvinsi() {
        $result = DB::table("provinces")->get();
        return $result;
      }
  
    public function jsonKabupaten($id){
    $select = array('regencies.id','regencies.name');
    $result = DB::table('regencies')->select($select)
        ->join('provinces','provinces.id','=','regencies.province_id')
        ->where('provinces.id','=',$id)
        ->orderBy('regencies.name')->get();
    return $result;
    }
    public function jsonKecamatan($id){
        $select = array('districts.id','districts.name');
        $result = DB::table('districts')->select($select)
        ->join('regencies','regencies.id','=','districts.regency_id')
        ->where('regencies.id','=',$id)
        ->orderBy('districts.name')->get();
        return $result;
    }
    public function jsonKelurahan($id){
        $select = array('villages.id','villages.name');
        $result = DB::table('villages')->select($select)
        ->join('districts','districts.id','=','villages.district_id')
        ->where('districts.id','=',$id)
        ->orderBy('villages.name')->get();
        return $result;
    }
}
