<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class NOC extends Model
{
    protected $table = "noc";
    protected $primaryKey = "noc_id";
    protected $fillable = [
        'company_id',
        'user_id',
    ];
    public $timestamps = false;
}
