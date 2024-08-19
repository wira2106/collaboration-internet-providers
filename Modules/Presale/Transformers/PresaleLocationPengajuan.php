<?php

namespace Modules\Presale\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PresaleLocationPengajuan extends Resource
{
    public function toArray($request)
    {
        return [
            'presale_id' => $this->presale_id,
            'icon' => url("/modules/presale/img/pengajuan.ico"),
            'position' => [
                'lat' => $this->lat,
                'lng' => $this->lon,
            ],
        ];
    }
}
