<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class WorkingDayTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->hari_kerja_id,
            'day' => $this->hari,
            'jam_mulai' => '2021-01-01 ' . $this->jam_mulai,
            'jam_selesai' => '2021-01-01 ' . $this->jam_selesai,
        ];
    }
}