<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class CompanyTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'company__company_translations';
}
