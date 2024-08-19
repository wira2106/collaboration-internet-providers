<?php

namespace Modules\Saldo\Entities;

use Illuminate\Database\Eloquent\Model;

class withdrawTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'saldo__withdraw_translations';
}
