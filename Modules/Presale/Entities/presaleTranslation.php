<?php

namespace Modules\Presale\Entities;

use Illuminate\Database\Eloquent\Model;

class presaleTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'presale__presale_translations';
}
