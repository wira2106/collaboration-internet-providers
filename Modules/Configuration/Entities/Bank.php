<?php

namespace Modules\Configuration\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Bank extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'bank';
    protected $primaryKey = "bank_id";
    public $translatedAttributes = [];
    protected $fillable = [
           'bank_id',
           'namaBank',
           'logo',
           'created_at',
           'created_by',
           'updated_at',
           'updated_by',
           'deleted_at'
        ];

    public function getBank(){
    	$data = DB::table("bank")->get();    	
    	return $data;
    }
}
