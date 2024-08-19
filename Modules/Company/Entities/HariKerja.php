<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class HariKerja extends Model
{
    protected $table = "hari_kerja";
    protected $fillable = [
        'company_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];
    protected $primaryKey = "hari_kerja_id";
    public $timestamps = false;

    public function SelectableRange($company_id) {
        $jam_start = self::where('company_id', $company_id)
                        ->orderBy('jam_mulai', 'asc')
                        ->first();
        $jam_end = self::where('company_id', $company_id)
                    ->orderBy('jam_selesai', 'desc')
                    ->first();
        $time = [
            "00:00:00",
            "23:59:59"
        ];
        if($jam_start) {
            $jam_start = $jam_start->jam_mulai;
            $jam_selesai = $jam_end->jam_selesai;
            $time = [$jam_start, $jam_selesai];
        }

        return $time;
    }
}
