<?php

namespace Modules\Presale\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusPresale extends Model
{
    protected $fillable = [];
    protected $table = "status_presales";
    protected $primaryKey = "status_id";
}
