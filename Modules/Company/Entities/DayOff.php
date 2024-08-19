<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    protected $table = "hari_libur";
    protected $fillable = [
        'company_id',
        'tgl_libur',
        'deskripsi_libur',
        'created_by',
        'created_at'
    ];
    protected $primaryKey = "hari_libur_id";
    public $timestamps = false;
}
