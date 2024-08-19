<?php

namespace Modules\Pelanggan\Entities;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SaldoBiayaPelanggan extends Model
{
    protected $table = 'saldo_biaya_pelanggan';
    public $translatedAttributes = [];
    protected $fillable = [
        'total_biaya',
        'saldo_mengendap',
        'biaya_admin',
        'persentase_biaya_admin',
        'biaya_osp',
        'persentase_biaya_osp',
        'pengembalian_isp',
        'settlement',
        'mrc',
        'otc'
    ];
    protected $primaryKey = "id";
    public $timestamps = false;


  
}
