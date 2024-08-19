<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TipeLogTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'value' => $this->tipe,
            'label' => ucwords($this->tipe)
        ];
    }
}
