<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class CompanyForSelectTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'company_id' => $this->company_id,
            'name' => $this->name,
        ];
    }
}