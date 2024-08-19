<?php

namespace Modules\Peralatan\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    // use Translatable;
    use SoftDeletes;
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';
    public $translatedAttributes = [];
    public $timestamps = false;
    protected $fillable = ['barang_id', 'nama_barang', 'tipe_qty', 'harga_per_pcs', 'created_at', 'updated_at', 'deleted_at'];
}
