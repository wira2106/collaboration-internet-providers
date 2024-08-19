<?php

namespace Modules\Pelanggan\Entities;

use Illuminate\Database\Eloquent\Model;

class PengajuanJadwal extends Model
{

    protected $table = 'pengajuan_jadwal';
    public $translatedAttributes = [];
    protected $fillable = [
        "pelanggan_id",
        "keterangan",
        "tgl_rekomendasi",
        "type",
        "created_at",
        "created_by",
        "status"
    ];
    protected $primaryKey = "pengajuan_id";
    public $timestamps = false;
}