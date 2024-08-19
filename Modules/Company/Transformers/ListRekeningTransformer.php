<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
class ListRekeningTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            "bank_account_id" => $this->bank_account_id,
            "bank_id" => $this->bank_id,
            "atas_nama" => $this->atas_nama,
            "rekening" => $this->rekening,
            "created_at" => date('d M Y', strtotime($this->created_at)),
            "namaBank" => $this->namaBank,
            'urls' => [
                'delete_url' => Auth::user()->hasAccess('company.companies.detail') ? route('api.company.rekening.destroy', $this->bank_account_id) : null,                
            ],
        ];
    }
}