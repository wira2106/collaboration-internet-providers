<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class DiskonPaketBerlangganan extends Model
{
    protected $table = 'diskon_paket_berlangganan';
    protected $primaryKey = 'biaya_id';
    public $timestamps = false;
    protected $fillable = ['biaya_id', 'paket_id', 'diskon', 'start_pelanggan', 'end_pelanggan'];
}
