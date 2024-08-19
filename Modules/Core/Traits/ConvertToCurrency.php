<?php

namespace Modules\Core\Traits;

trait ConvertToCurrency
{
    public function rupiah($angka) {
        $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
        return $hasil_rupiah;
    }
}
