<?php

namespace Modules\Presale\Entities;

use Illuminate\Database\Eloquent\Model;

class ActivePresale extends Model
{
    protected $fillable = [
        'osp_id',
        'presale_id',
        'confirmed_by',
        'confirmed_at'
    ];
    protected $table = "active_presales";
    protected $primaryKey = "active_presale_id";
    public $timestamps = false;
}
