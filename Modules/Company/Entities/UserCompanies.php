<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class UserCompanies extends Model
{
    protected $fillable = [
        'user_id',
        'company_id'
    ];
    protected $table = "user_companies";
    public $timestamps = false;
    
}
