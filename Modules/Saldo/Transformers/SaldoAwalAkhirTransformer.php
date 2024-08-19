<?php

namespace Modules\Saldo\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class SaldoAwalAkhirTransformer extends Resource
{
    public function toArray($request)
    {        
        $saldo_awal = $this['saldo_awal'];
        $saldo_akhir = $this['saldo_akhir'];
        return [
            'saldo_awal' => $saldo_awal,
            'saldo_akhir' => $saldo_akhir,
            'saldo_awal_rp' => 'Rp. '.number_format($saldo_awal,0,',','.'),
            'saldo_akhir_rp' => 'Rp. '.number_format($saldo_akhir,0,',','.'),
        ];
    }
}
