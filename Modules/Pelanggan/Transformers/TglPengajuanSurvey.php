<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TglPengajuanSurvey extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pengajuan_id' => $this->pengajuan_id,
            'status' => $this->status,
            'tgl_survey' => $this->tgl_survey,
            // 'jam_survey' => $this->jam_survey,
            'jam_survey' => date('H:i', strtotime($this->jam_survey)),
            'full_name' => date('d M Y H:i', strtotime($this->tgl_survey . " " . $this->jam_survey)),
        ];
    }
}
