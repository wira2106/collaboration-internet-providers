<?php

namespace Modules\Peralatan\Entities;

use Illuminate\Database\Eloquent\Model;

class BarangTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'peralatan__barang_translations';
}
