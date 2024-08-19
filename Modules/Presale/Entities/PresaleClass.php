<?php

namespace Modules\Presale\Entities;

use Illuminate\Database\Eloquent\Model;

class PresaleClass extends Model
{
    protected $fillable = [];
    protected $table = "presale_class";
    protected $primaryKey = "class_id";
}
