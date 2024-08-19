<?php

namespace Modules\Peralatan\Entities;

use Illuminate\Database\Eloquent\Model;

class PeralatanTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'peralatan__peralatan_translations';
}
