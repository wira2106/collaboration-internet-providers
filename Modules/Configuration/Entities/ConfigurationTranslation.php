<?php

namespace Modules\Configuration\Entities;

use Illuminate\Database\Eloquent\Model;

class ConfigurationTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'configuration__configuration_translations';
}
