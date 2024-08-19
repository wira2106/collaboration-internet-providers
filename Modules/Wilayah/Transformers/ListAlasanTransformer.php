<?php

namespace Modules\Wilayah\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Modules\Wilayah\Traits\TransformStatusRequestWilayah;

class ListAlasanTransformer extends Resource
{
    use TransformStatusRequestWilayah;
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'request_wilayah_id' => $this->request_wilayah_id,
            'alasan' => $this->alasan,
            'status' => $this->status,
            'created_at' => date('d M Y H:i:s', strtotime($this->created_at)),
            'readable_status' => $this->transform_status($this->status)
        ];
    }
}