<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model
{
    protected $fillable = [];
    protected $table = "hari_kerja";
    protected $primaryKey = "hari_kerja_id";
    public $timestamps = false;

}
