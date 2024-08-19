<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
class CompanyTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'company_id' => $this->company_id,
            'name' => $this->name,
            'type' => $this->type,
            'rating' => $this->rating ? $this->rating : 0,
            'created_at' => $this->created_at,
            'logo' => url('/uploadgambar') . "/" . $this->logo_perusahaan ?: "nologo.png",
            'urls' => [
                'delete_url' => Auth::user()->hasAccess('company.companies.destroy') ? route('api.company.company.destroy', $this->company_id) : null,
                'edit_url' => Auth::user()->hasAccess('company.companies.edit') ? route('admin.company.company.edit', $this->company_id) : null
            ],
        ];
    }
}