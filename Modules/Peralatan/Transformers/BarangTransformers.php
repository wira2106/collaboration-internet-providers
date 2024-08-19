<?php

namespace Modules\Peralatan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class BarangTransformers extends Resource
{
    public function toArray($request)
    {
        return [
            'barang_id' => $this->barang_id,
            'nama_barang' => $this->nama_barang,
            'tipe_qty' => $this->tipe_qty,
            'harga_per_pcs' => number_format($this->harga_per_pcs),

            'urls' => [
                'delete_url' => route('api.peralatan.barang.destroy', $this->barang_id),
            ],
        ];
    }
}
