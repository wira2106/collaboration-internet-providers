<?php

namespace Modules\Peralatan\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perangkat extends Model
{
    // use Translatable;
    use SoftDeletes;
    protected $table = 'perangkat';
    protected $primaryKey = 'perangkat_id';
    public $timestamps = false;
    public $translatedAttributes = [];
    protected $fillable = ['nama_perangkat', 'created_at', 'updated_at', 'deleted_at'];
}
