<?php

namespace Modules\Saldo\Entities;

use Illuminate\Database\Eloquent\Model;

class topupTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'saldo__topup_translations';
}
