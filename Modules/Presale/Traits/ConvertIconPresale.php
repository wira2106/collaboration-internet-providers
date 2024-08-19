<?php

namespace Modules\Presale\Traits;

use Modules\Presale\Entities\PresaleClass;

trait ConvertIconPresale 
{
    private $presale_class;
    private $icon_name_status = ['','','_active', '_exist', '_uncover', '_minat_tidak_tercover', '_blacklist', '_vip'];


    public function getIconUrl($classId, $statusId) {
        $this->presale_class = PresaleClass::where('deleted', 0)->get()->toArray();
        $icon = $this->searchIconNameById($classId);
        $status = $this->icon_name_status[$statusId ? $statusId : 0];
        return url("/modules/presale/img/icon_marker/$icon"."$status.ico");
    }

    private function searchIconNameById($classId) {
        $key = array_search($classId, array_column($this->presale_class, 'class_id'));
        if($key !== false) {
            return $this->presale_class[$key]['icon'];
        }
        return null;
    }


}
