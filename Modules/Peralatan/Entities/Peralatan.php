<?php

namespace Modules\Peralatan\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use Translatable;

    protected $table = 'peralatan__peralatans';
    public $translatedAttributes = [];
    protected $fillable = [];
}
