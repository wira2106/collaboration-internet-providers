<?php

namespace Modules\Wilayah\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Modules\Wilayah\Traits\TransformStatusPresaleWilayah;

class ListWilayahTransformer extends Resource
{
    use TransformStatusPresaleWilayah;
    public function toArray($request)
    {
        return [
            'wilayah_id' => $this->wilayah_id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            "open" => $this->open,
            'nama_company' => $this->nama_company,
            'provinces_id' => $this->provinces_id,
            'regencies_id' => $this->regencies_id,
            'districts_id' => $this->districts_id,
            'villages_id' => $this->villages_id,
            'created_at' => date('d M Y H:i', strtotime($this->created_at)),
            'status_presale' => $this->status_presale,
            'readable_status_presale' => $this->transform_status($this->status_presale),
            'urls' => [
                'delete_url' => Auth::user()->hasAccess('wilayah.wilayahs.destroy') ? route('api.wilayah.wilayah.destroy', $this->wilayah_id) : null,                
                'edit_url' => Auth::user()->hasAccess('wilayah.wilayahs.update') ? route('admin.wilayah.wilayah.edit', $this->wilayah_id) : null
            ],
        ];
    }
}