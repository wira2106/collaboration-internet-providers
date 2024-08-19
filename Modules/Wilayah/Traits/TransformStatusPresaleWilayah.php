<?php

namespace Modules\Wilayah\Traits;

trait TransformStatusPresaleWilayah {
    public function transform_status($status){
        $status_name = $status;

        if($status == 'request') $status_name = 'Request';
        if($status == 'review') $status_name = 'Review';
        if($status == 'finish') $status_name = 'Selesai';

        return $status_name;
    }
}