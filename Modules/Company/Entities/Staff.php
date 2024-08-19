<?php

namespace Modules\Company\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use Translatable;

    protected $table = 'company__staff';
    public $translatedAttributes = [];
    protected $fillable = [];
}
