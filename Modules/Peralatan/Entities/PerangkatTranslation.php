<?php

namespace Modules\Peralatan\Entities;

use Illuminate\Database\Eloquent\Model;

class PerangkatTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'peralatan__perangkat_translations';
}
