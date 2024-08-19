<?php

namespace Modules\Presale\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Modules\Presale\Entities\PresaleClass;
use Modules\Presale\Entities\StatusPresale;

class PresaleLocationTransformer extends Resource
{
    private $icon_name_status = ['','','_active', '_exist', '_uncover', '_minat_tidak_tercover', '_blacklist', '_vip'];

    private $presale;
    // public function __construct($resource)
    // {
    //     $this->presale_class = PresaleClass::where('deleted', 0)->get()->toArray();
    //     dd($this->presale_class);
    //     parent::__construct($resource);
    // }
    public function toArray($request)
    {
        return [
            'presale_id' => $this->presale_id,
            'icon' => $this->getIconUrl($this->icon, $this->status_id),
            'position' => [
                'lat' => (float) $this->lat,
                'lng' => (float) $this->lon,
            ],
            'visibility' => true,
            'end_point_id' => $this->end_point_id,
            'panjang_kabel' => $this->panjang_kabel,
            'site_id' => $this->site_id,
            'hasAccess' => $this->hasAccess()
        ];
    }

    private function hasAccess() {
        return !($this->active_presale_id === null);
    }

    private function getIconUrl($icon, $statusId) {
        if(!Auth::user()->hasAllAccessCompanies()) {
            if(!$this->hasAccess()) {
                return url('/modules/presale/img/pengajuan.ico');
            }
        }


        // $icon = $this->searchIconNameById($classId);
        $status = $this->icon_name_status[$statusId ? $statusId : 0];
        return url("/modules/presale/img/icon_marker/$icon"."$status.ico");
    }

    // private function searchIconNameById($classId) {
    //     $key = array_search($classId, array_column($this->presale_class, 'class_id'));
    //     if($key !== false) {
    //         return $this->presale_class[$key]['icon'];
    //     }
    //     return null;
    // }


}
