<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use SoftDeletes;
    protected $table = "bank_account";
    protected $fillable = [
        'company_id',
        'bank_id',
        'atas_nama',
        'rekening',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
    protected $primaryKey = "bank_account_id";
    public $timestamps = true;   
}
