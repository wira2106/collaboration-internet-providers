<?php

namespace Modules\Presale\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PathlinePresaleTransformer extends Resource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'presale_id' => $this->presale_id,
            'routes' => $this->routes()
        ];
    }




}
