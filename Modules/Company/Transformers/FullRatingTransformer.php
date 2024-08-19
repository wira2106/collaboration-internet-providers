<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\Resource;
class FullRatingTransformer extends Resource
{
    public function toArray($request)
    {
        // dd($this);
        return [
            'full_name' => $this->full_name,
            'name' => $this->name,
            'rating' => $this->rating,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}