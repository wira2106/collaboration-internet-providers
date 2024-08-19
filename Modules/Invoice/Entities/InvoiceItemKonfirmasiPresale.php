<?php

namespace Modules\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class InvoiceItemKonfirmasiPresale extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'invoice_id',
        'endpoint_id',
        'amount',
    ];
    
    protected $table = 'invoice_item_konfirmasi_presale';
    
}
