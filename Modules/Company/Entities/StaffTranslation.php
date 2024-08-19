<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class StaffTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'company__staff_translations';
}
