<?php

namespace Modules\Pelanggan\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Suspend extends Model
{
    // use Translatable;

    protected $table = 'suspend';
    public $translatedAttributes = [];
    protected $fillable = ["pelanggan_id","alasan","tgl_suspend","created_at","created_by"];
    protected $primaryKey = "suspend_id";
    public $timestamps = false;
}