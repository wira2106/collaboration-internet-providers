<?php

namespace Modules\Core\Traits;

trait ConvertToTimer
{
    public function convertToTime($value, $withoutSecond=false)
    {
        $time = "";
        $hari = (int)($value / 86400);
        $jam = (int)(($value%86400)/3600);
        $menit = (int)((($value%86400)%3600)/60);
        $detik = (($value%86400)%3600)%60;
    
        if ($hari > 0) {
            $time .= $hari . " Hari ";
        }
        if ($jam > 0) {
            $time .= $jam . " Jam ";
        }
        if ($menit > 0) {
            $time .= $menit . " Menit ";
        }
        if(!$withoutSecond){
            if($detik > 0){
                $time .= $detik . " Detik";
            }
        }
    
        return $time;
    }
}
