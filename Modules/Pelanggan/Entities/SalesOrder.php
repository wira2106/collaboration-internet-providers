<?php

namespace Modules\Pelanggan\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use Translatable;

    protected $table = 'salesorder';
    public $translatedAttributes = [];
    protected $fillable = [];
}
