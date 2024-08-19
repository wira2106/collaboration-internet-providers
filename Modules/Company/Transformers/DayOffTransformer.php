<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
class DayOffTransformer extends Resource
{
    public function toArray($request)
    {
        // dd($this);
        return [
            'hari_libur_id' => $this->hari_libur_id,
            'tgl_libur' => $this->tgl_libur,
            'deskripsi_libur' => $this->deskripsi_libur,
        ];
    }
}