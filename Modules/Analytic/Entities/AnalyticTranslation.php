<?php

namespace Modules\Analytic\Entities;

use Illuminate\Database\Eloquent\Model;

class AnalyticTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'analytic__analytic_translations';
}
