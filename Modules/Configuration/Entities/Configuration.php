<?php

namespace Modules\Configuration\Entities;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    public $timestamps = false;
    protected $table = 'setting';
    public $translatedAttributes = [];
    protected $fillable = [
            'admin_fee',
            'persentase_otc_mrc',
            'persentase_refund_osp',
            'persentase_refund_openaccess',
            'sla_odp',
            'sla_join_box',
            'sla_fixing_stack',
        ];
}
