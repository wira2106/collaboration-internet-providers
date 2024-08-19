<?php

namespace Modules\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class InvoicePelanggan extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'invoice_pelanggan_id';
    protected $fillable = [
        'request_wilayah_id',
        'invoice_id'
    ];
    
    protected $table = 'invoice_pelanggan';

}
