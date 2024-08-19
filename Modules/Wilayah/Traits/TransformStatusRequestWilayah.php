<?php
namespace Modules\Wilayah\Traits;
trait TransformStatusRequestWilayah {
    public function transform_status($status) {
        $status_name = $status;

        if($status == 'confirmed') $status_name = trans('wilayah::pengajuans.status.confirmed');
        if($status == 'rejected') $status_name = trans('wilayah::pengajuans.status.rejected');
        if($status == 'disagree') $status_name = trans('wilayah::pengajuans.status.disagree');


        return $status_name;
    }
}