<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class SlotInstalasiPengajuan extends Resource
{
    public function toArray($request)
    {
        return [
            'slot_id' => $this->slot_id,
            'name' => $this->name,
            'jam_start' => $this->jam_start,
            'jam_end' => $this->jam_end,
            'full_name' => $this->name." (".date('H:i', strtotime($this->jam_start))." - ".date('H:i', strtotime($this->jam_end)).")",
            'updated_at' => $this->updated_at ? $this->updated_at : $this->created_at,
        ];
    }
}