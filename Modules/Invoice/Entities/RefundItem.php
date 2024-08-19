<?php

namespace Modules\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class RefundItem extends Model 
{

    protected $table = 'refund_item';
    public $translatedAttributes = [];
    protected $primaryKey = 'refund_id';
    public $timestamps = false;
    protected $fillable = [
        'invoice_item_id',
        'refund_amount',
        'status',
        'refund_type',
        'description',
        'created_at',
    ];

    

    


}
