<?php

namespace Modules\Wilayah\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
class ListWilayahTransformer extends Resource
{
    private $wilayah_id;

    public function __construct($resource, $wilayah_id)
    {
        parent::__construct($resource);
        $this->wilayah_id = $wilayah_id;
    }
    public function toArray($request)
    {
        return [
            'wilayah_id' => $request['wilayah_id'],
            'nama_end_point' => $request['nama_end_point'],
            'tipe' => $request['tipe'],
            'lat_end_point' => $request['position']['lat'],
            'lon_end_point' => $request['position']['lng'],
            'address' => $request['address']
        ];
    }
}