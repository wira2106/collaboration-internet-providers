<?php

namespace Modules\Peralatan\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alat extends Model
{
    // use Translatable;
    use SoftDeletes;
    protected $table = 'alat';
    protected $primaryKey = 'alat_id';
    public $timestamps = false;
    public $translatedAttributes = [];
    protected $fillable = ['alat_id', 'nama_alat', 'created_at', 'updated_at', 'deleted_at'];
}
