<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlotInstalasi extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "jam_start",
        "jam_end",
        "created_by",
        "updated_by",
        "name",
        "company_id"
    ];
    protected $table = 'slot_instalasi';
    protected $primaryKey = 'slot_id';


    public function SelectableRange($company_id) {
        $jam_end = self::where('company_id', $company_id)
                        ->orderBy('jam_end', 'desc')
                        ->first();
        
        $time = (new HariKerja())->SelectableRange($company_id);

        if($jam_end) {
            $time = [
                $jam_end->jam_end,
                $time[1]
            ];
        }

        return $time;
    }
}
