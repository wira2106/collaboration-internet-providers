<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class SlotInstalasiTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'slot_id' => $this->slot_id,
            'name' => $this->name,
            'jam_start' => $this->jam_start,
            'jam_end' => $this->jam_end,
            'full_name' => $this->full_name,
            'updated_at' => $this->updated_at ? date('d M Y H:i', strtotime($this->updated_at)) : "-",
            'urls' => [
                'delete_url' => Auth::user()->hasAccess('company.slot_instalasi.edit') ? route('api.company.slotinstalasi.delete', $this->slot_id) : null
            ]
        ];
    }
}