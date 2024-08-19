<?php

namespace Modules\Presale\Entities;

use Illuminate\Database\Eloquent\Model;

class endpointTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'presale__endpoint_translations';
}
