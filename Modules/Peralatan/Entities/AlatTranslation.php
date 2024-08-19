<?php

namespace Modules\Peralatan\Entities;

use Illuminate\Database\Eloquent\Model;

class AlatTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'peralatan__alat_translations';
}
