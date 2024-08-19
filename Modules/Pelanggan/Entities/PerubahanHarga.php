<?php

namespace Modules\Pelanggan\Entities;
use Illuminate\Database\Eloquent\Model;

class PerubahanHarga extends Model
{
    protected $table = 'perubahan_harga';
    protected $primaryKey = "perubahan_harga_id";
    protected $fillable = [
        'pelanggan_id',
        'harga_otc',
        'harga_mrc',
        'status',
        'keterangan',
        'created_at',
        'created_by',
        'paket_id'
    ];
    public $timestamps = false;
}
