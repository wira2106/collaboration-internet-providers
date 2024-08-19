<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    protected $table = "teknisi";
    protected $primaryKey = "teknisi_id";
    protected $fillable = [
        'company_id',
        'user_id',
    ];
    public $timestamps = false;

}
