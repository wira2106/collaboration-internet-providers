<?php

namespace Modules\Pelanggan\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class SaldoBiayaPelanggan extends Resource
{
	public function toArray($request)
	{
		$selisih = $this->saldo_mengendap - $this->total_biaya;
		if ($selisih == 0) {
			$status = "pas";
		} else if ($selisih < 0) {
			$status = "kurang";
		} else {
			$status = "lebih";
		}
		return [
			'total_biaya' => $this->total_biaya,
			'saldo_mengendap' => $this->saldo_mengendap,
			'total_biaya_rp' => "Rp. " . number_format($this->total_biaya, 0, ',', '.'),
			'saldo_mengendap_rp' => "Rp. " . number_format($this->saldo_mengendap, 0, ',', '.'),
			'status' => $status,
			'selisih' => "Rp. " . number_format($selisih, 0, ',', '.')
		];
	}
}
